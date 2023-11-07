<?php

namespace App\Entites;

use DateTime;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

#[Entity]
class Adherent
{
    #[Id]
    #[Column(type: Types::INTEGER)]
    #[GeneratedValue]
    private int $id;
    #[Column(name: "numero_adherent",length: 9)]
    private string $numeroAdherent;
    #[Column(length: 80)]
    private string $prenom;
    #[Column(length: 80)]
    private string $nom;
    #[Column(length: 70)]
    private string $email;
    #[Column(name: "date_adhesion", type: Types::DATETIME_MUTABLE)]
    private DateTime $dateAdhesion;

    public function __construct()
    {
    }

    public function setNumeroAdherent(string $numero): void
    {
        $this->numeroAdherent = $numero;
    }

    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setDateAdhesion(DateTime $dateAdhesion): void
    {
        $this->dateAdhesion = $dateAdhesion;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNumeroAdherent(): string
    {
        return $this->numeroAdherent;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getDateAdhesion(): DateTime
    {
        return $this->dateAdhesion;
    }
}