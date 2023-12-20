<?php

namespace App\UserStories\EmprunterMedia;

use App\Entites\Adherent;
use App\Entites\Emprunt;
use App\Entites\Media;
use App\Entites\StatutEmprunt;
use App\Services\GenerateurNumeroEmprunt;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use function PHPUnit\Framework\isNull;

class EmprunterMedia
{
    private EntityManagerInterface $entityManager;
    private GenerateurNumeroEmprunt $generateur;
    private ValidatorInterface $validateur;

    /**
     * @param EntityManagerInterface $entityManager
     * @param GenerateurNumeroEmprunt $generateur
     * @param ValidatorInterface $validateur
     */
    public function __construct(EntityManagerInterface $entityManager, GenerateurNumeroEmprunt $generateur, ValidatorInterface $validateur)
    {
        $this->entityManager = $entityManager;
        $this->generateur = $generateur;
        $this->validateur = $validateur;
    }

    public function execute(EmprunterMediaRequete $requete)
    {
        $erreurs = $this->validateur->validate($requete);
        if (count($erreurs) > 0) {
            $message = "";
            foreach ($erreurs as $violation) {
                $message .= $violation->getMessage().PHP_EOL;
            }
            throw new \Exception($message);
        }
        if (!preg_match('/(AD-)[0-9]{6}/',$requete->adherent)) {
            throw new \Exception("Le numéro d'adhérent n'est pas valide !");
        }
        $adherentRepo = $this->entityManager->getRepository(Adherent::class);
        $adherent = $adherentRepo->findOneBy(["numeroAdherent" => $requete->adherent]);
        $media = $this->entityManager->find(Media::class,$requete->media);
        if (!isset($media)) {
            throw new \Exception("Le média à emprunter est inexistant !");
        }
        if (!isset($adherent)) {
            throw new \Exception("Cet adhérent est inexistant !");
        }
        if ($media->getStatut() != StatutEmprunt::STATUT_DISPONIBLE) {
            throw new \Exception("Le média choisi n'est pas disponible !");
        }
        if (!$adherent->checkAdhesionValide()) {
            throw new \Exception("L'adhérent choisi ne possède pas d'adhésion valide !");
        }
        $lastId = $this->entityManager->getConnection()->executeQuery("SELECT MAX(id) FROM emprunt")->fetchOne();
        if (is_null($lastId)) {
            $lastId = 0;
        }
        $this->entityManager->getConnection()->beginTransaction();
        try {
            $emprunt = new Emprunt();
            $emprunt->setNumeroEmprunt($this->generateur->generer($lastId));
            $emprunt->setDateEmprunt(new \DateTime());
            $emprunt->setDateRetourEstimee((clone $emprunt->getDateEmprunt())->add(\DateInterval::createFromDateString("+" . $media->getDureeEmprunt() . "days")));
            $emprunt->setAdherent($adherent);
            $emprunt->setMedia($media);
            $media->setStatut(StatutEmprunt::STATUT_EMPRUNTE);
            $this->entityManager->persist($emprunt);
            if (!$this->entityManager->contains($emprunt)) {
                throw new \Exception("Un problème est survenu lors de l'enregistrement dans la base de données !");
            }
            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->getConnection()->rollBack();
            throw $e;
        }
    }
}