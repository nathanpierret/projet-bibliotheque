<?php

namespace App\Entites;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name: "Type", type: "string")]
#[ORM\DiscriminatorMap(["livre"=>"Livre","blu-ray"=>"BluRay","magazine"=>"Magazine"])]
class Media
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    protected int $id;
    #[ORM\Column(type: "string", length: 70)]
    protected string $titre;
    #[ORM\Column(type: "integer")]
    protected int $dureeEmprunt;
    #[ORM\Column(type: "string")]
    protected string $statut;
    #[ORM\Column(type: "string")]
    protected string $dateCreation;

    public function __construct()
    {

    }

    public function setTitre(string $titre): void
    {
        $this->titre = $titre;
    }

    public function setDureeEmprunt(int $dureeEmprunt): void
    {
        $this->dureeEmprunt = $dureeEmprunt;
    }

    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }

    public function setDateCreation(string $dateCreation): void
    {
        $this->dateCreation = $dateCreation;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitre(): string
    {
        return $this->titre;
    }

    public function getDureeEmprunt(): int
    {
        return $this->dureeEmprunt;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function getDateCreation(): string
    {
        return $this->dateCreation;
    }

}