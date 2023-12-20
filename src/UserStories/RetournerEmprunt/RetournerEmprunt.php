<?php

namespace App\UserStories\RetournerEmprunt;

use App\Entites\Emprunt;
use App\Entites\Media;
use App\Entites\StatutEmprunt;
use Doctrine\ORM\EntityManagerInterface;
use function PHPUnit\Framework\isEmpty;

class RetournerEmprunt
{
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function execute(?string $numeroEmprunt=""): bool {
        if (empty($numeroEmprunt)) {
            throw new \Exception("Le numéro d'emprunt est obligatoire !");
        }
        if (!preg_match('/(EM-)[0-9]{9}/',$numeroEmprunt)) {
            throw new \Exception("Le numéro d'emprunt n'est pas valide !");
        }
        $empruntRepo = $this->entityManager->getRepository(Emprunt::class);
        $emprunt = $empruntRepo->findOneBy(["numeroEmprunt" => $numeroEmprunt]);
        if (!isset($emprunt)) {
            throw new \Exception("L'emprunt choisi n'existe pas !");
        }
        if ($emprunt->getDateRetour() !== null) {
            throw new \Exception("Cet emprunt a déjà été restitué !");
        }
        $this->entityManager->getConnection()->beginTransaction();
        try {
            $emprunt->setDateRetour(new \DateTime());
            $media = $this->entityManager->find(Media::class,$emprunt->getMedia()->getId());
            $media->setStatut(StatutEmprunt::STATUT_DISPONIBLE);
            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->getConnection()->rollBack();
            throw $e;
        }
    }
}