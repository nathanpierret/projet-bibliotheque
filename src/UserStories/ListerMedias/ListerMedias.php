<?php

namespace App\UserStories\ListerMedias;

use App\Entites\Media;
use App\Entites\StatutEmprunt;
use Doctrine\ORM\EntityManagerInterface;

class ListerMedias
{
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function execute(): array {
        $mediaRepository = $this->entityManager->getRepository(Media::class);
        $connexion = $this->entityManager->getConnection();
        $requete = "SELECT m.id, m.titre, m.statut, m.dateCreation, m.Type
                    FROM media as m
                    WHERE m.statut = :statut
                    ORDER BY m.dateCreation DESC";
        $resultSet = $connexion->executeQuery($requete, ['statut' => StatutEmprunt::STATUT_NOUVEAU]);
        /*$query = $this->entityManager->createQuery(
            "SELECT m.id, m.titre, m.dureeEmprunt, m.statut, m.dateCreation
            FROM App\Entites\Media as m
            ORDER BY m.dateCreation DESC");*/
        return $resultSet->fetchAllAssociative();
    }
}