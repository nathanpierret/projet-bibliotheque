<?php

namespace App\UserStories\EmprunterMedia;

use App\Entites\Adherent;
use App\Entites\Emprunt;
use App\Entites\Media;
use App\Entites\StatutEmprunt;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EmprunterMedia
{
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validateur;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validateur
     */
    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validateur)
    {
        $this->entityManager = $entityManager;
        $this->validateur = $validateur;
    }

    public function execute(?int $idMedia=null, ?string $numeroAdherent="")
    {
        if (!isset($idMedia)) {
            throw new \Exception("Le média à emprunter n'existe pas dans la base de données !");
        }
        if (!isset($numeroAdherent)) {
            throw new \Exception("L'adhérent n'existe pas dans la base de données !");
        }
        $adherentRepo = $this->entityManager->getRepository(Adherent::class);
        $adherent = $adherentRepo->findBy(["numeroAdherent" => $numeroAdherent]);
        $media = $this->entityManager->find(Media::class,$idMedia);
        if ($media->getStatut() != StatutEmprunt::STATUT_DISPONIBLE) {
            throw new \Exception("Le média choisi n'est pas disponible !");
        }
        if (!$adherent[0]->checkAdhesionValide()) {
            throw new \Exception("L'adhérent choisi ne possède pas d'adhésion valide !");
        }
        $emprunt = new Emprunt();
        $emprunt->setNumeroEmprunt(/* A remplir */);
        $emprunt->setDateEmprunt(new \DateTime());
        $emprunt->setDateRetourEstimee(($emprunt->getDateEmprunt())->add(\DateInterval::createFromDateString("+".$media->getDureeEmprunt()."days")));
        $emprunt->setAdherent($adherent[0]);
        $emprunt->setMedia($media);
        $this->entityManager->persist($emprunt);
        if (!$this->entityManager->contains($emprunt)) {
            throw new \Exception("Un problème est survenu lors de l'enregistrement dans la base de données !");
        }
        $this->entityManager->flush();
        return true;
    }
}