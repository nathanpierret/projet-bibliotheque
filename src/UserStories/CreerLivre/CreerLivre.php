<?php

namespace App\UserStories\CreerLivre;

use App\Entites\Livre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreerLivre
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

    public function execute(CreerLivreRequete $requete): bool {
        // Valider les données en entrée (de la requête)
        $erreurs = $this->validateur->validate($requete);
        if (count($erreurs) > 0) {
            $message = "";
            foreach ($erreurs as $violation) {
                $message .= $violation->getMessage().PHP_EOL;
            }
            throw new \Exception($message);
        }
        // Vérifier que l'ISBN n'existe pas déjà
        $repository = $this->entityManager->getRepository(Livre::class);
        $livresISBN = $repository->count(['isbn' => $requete->isbn]);
        if ($livresISBN > 0) {
            throw new \Exception("Cet ISBN est déjà utilisé !");
        }
        // Créer le Livre
        $livre = new Livre();
        $livre->setIsbn($requete->isbn);
        $livre->setTitre($requete->titre);
        $livre->setAuteur($requete->auteur);
        $livre->setNbPages($requete->nbPages);
        $livre->setDateCreation($requete->dateCreation);
        $livre->setStatut('Nouveau');
        $livre->setDureeEmprunt(21);
        // Enregistrer le livre en base de données
        $this->entityManager->persist($livre);
        if (!$this->entityManager->contains($livre)) {
            throw new \Exception("Un problème est survenu lors de l'enregistrement dans la base de données !");
        }
        $this->entityManager->flush();
        return true;
    }
}