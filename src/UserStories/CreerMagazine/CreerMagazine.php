<?php

namespace App\UserStories\CreerMagazine;

use App\Entites\Magazine;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreerMagazine
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

    public function execute(CreerMagazineRequete $requete): bool {
        // Valider les données en entrée
        $erreurs = $this->validateur->validate($requete);
        if (count($erreurs) > 0) {
            $message = "";
            foreach ($erreurs as $violation) {
                $message .= $violation->getMessage().PHP_EOL;
            }
            throw new \Exception($message);
        }
        // Créer le magazine
        $magazine = new Magazine();
        $magazine->setTitre($requete->titre);
        $magazine->setNumero($requete->numero);
        $magazine->setDateCreation((new \DateTime())->format("d/m/Y H:i:s"));
        $magazine->setDatePublication($requete->datePublication);
        $magazine->setStatut('Nouveau');
        $magazine->setDureeEmprunt(10);
        // Enregistrer le magazine en base de données
        $this->entityManager->persist($magazine);
        if (!$this->entityManager->contains($magazine)) {
            throw new \Exception("Un problème est survenu lors de l'enregistrement dans la base de données !");
        }
        $this->entityManager->flush();
        return true;
    }
}