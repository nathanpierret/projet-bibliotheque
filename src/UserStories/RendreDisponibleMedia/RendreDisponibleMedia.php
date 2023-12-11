<?php

namespace App\UserStories\RendreDisponibleMedia;

use App\Entites\Media;
use App\Entites\StatutEmprunt;
use Doctrine\ORM\EntityManagerInterface;

class RendreDisponibleMedia
{
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function execute(?int $idMedia=null): bool {
        if (!isset($idMedia)) {
            throw new \Exception("L'ID du média est obligatoire !");
        }
        $media = $this->entityManager->find(Media::class, $idMedia);
        if ($media === null) {
            throw new \Exception("Le média avec cet identifiant n'existe pas dans la Base de Données !");
        }
        if ($media->getStatut() != StatutEmprunt::STATUT_NOUVEAU) {
            throw new \Exception("Le média choisi ne possède pas le statut 'Nouveau' !");
        }
        // Changer le statut 'Nouveau' en 'Disponible'
        $media->setStatut(StatutEmprunt::STATUT_DISPONIBLE);
        // Enregistrer les changements dans la Base de Données
        $this->entityManager->persist($media);
        if (!$this->entityManager->contains($media)) {
            throw new \Exception("Un problème est survenu lors de l'enregistrement dans la base de données !");
        }
        $this->entityManager->flush();
        return true;
    }
}