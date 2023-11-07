<?php

namespace App\UserStories\CreerAdherent;

use App\Entites\Adherent;
use App\Services\GenerateurNumeroAdherent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreerAdherent
{
    private EntityManagerInterface $entityManager;
    private GenerateurNumeroAdherent $generateurNumeroAdherent;
    private ValidatorInterface $validateur;

    /**
     * @param EntityManagerInterface $entityManager
     * @param GenerateurNumeroAdherent $generateurNumeroAdherent
     * @param ValidatorInterface $validateur
     */
    public function __construct(EntityManagerInterface $entityManager, GenerateurNumeroAdherent $generateurNumeroAdherent, ValidatorInterface $validateur)
    {
        $this->entityManager = $entityManager;
        $this->generateurNumeroAdherent = $generateurNumeroAdherent;
        $this->validateur = $validateur;
    }

    public function execute(CreerAdherentRequete $requete) :  bool {

        // Valider les données en entrées (de la requête)

        // Vérifier que l'email n'existe pas déjà

        // Générer un numéro d'adhérent au format AD-999999
        $numeroAdherent = $this->generateurNumeroAdherent->generer();
        // Vérifier que le numéro n'existe pas déjà

        // Créer l'adhérent
        $adherent = new Adherent();
        $adherent->setNumeroAdherent($numeroAdherent);
        $adherent->setPrenom($requete->prenom);
        $adherent->setNom($requete->nom);
        $adherent->setEmail($requete->email);
        $adherent->setDateAdhesion(new \DateTime());
        // Enregistrer l'adhérent en base de données
        $this->entityManager->persist($adherent);
        $this->entityManager->flush();
        return true;
    }
}