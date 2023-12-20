<?php

namespace App\Entites;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

#[Entity]
class Emprunt
{
    #[Id]
    #[Column(type: Types::INTEGER)]
    #[GeneratedValue]
    private int $id;
    #[Column(name: "numero_emprunt", length: 12)]
    private string $numeroEmprunt;
    #[Column(name: "date_emprunt", type: Types::DATETIME_MUTABLE)]
    private DateTime $dateEmprunt;
    #[Column(name: "date_retour_estimee",type: Types::DATETIME_MUTABLE)]
    private ?DateTime $dateRetourEstimee;
    #[Column(name: "date_retour",type: Types::DATETIME_MUTABLE,nullable: true)]
    private ?DateTime $dateRetour;

    #[ORM\ManyToOne(targetEntity: Adherent::class)]
    private Adherent $adherent;
    #[ORM\ManyToOne(targetEntity: Media::class)]
    private Media $media;

    public function __construct()
    {
        $this->dateRetourEstimee = null;
        $this->dateRetour = null;
    }

    public function setNumeroEmprunt(string $numeroEmprunt): void
    {
        $this->numeroEmprunt = $numeroEmprunt;
    }

    public function setDateEmprunt(DateTime $dateEmprunt): void
    {
        $this->dateEmprunt = $dateEmprunt;
    }

    public function setDateRetourEstimee(DateTime $dateRetourEstimee): void
    {
        $this->dateRetourEstimee = $dateRetourEstimee;
    }

    public function setDateRetour(?DateTime $dateRetour): void
    {
        $this->dateRetour = $dateRetour;
    }

    public function setAdherent(Adherent $adherent): void
    {
        $this->adherent = $adherent;
    }

    public function setMedia(Media $media): void
    {
        $this->media = $media;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNumeroEmprunt(): string
    {
        return $this->numeroEmprunt;
    }

    public function getDateEmprunt(): DateTime
    {
        return $this->dateEmprunt;
    }

    public function getDateRetourEstimee(): ?DateTime
    {
        return $this->dateRetourEstimee;
    }

    public function getDateRetour(): ?DateTime
    {
        return $this->dateRetour;
    }

    public function getAdherent(): Adherent
    {
        return $this->adherent;
    }

    public function getMedia(): Media
    {
        return $this->media;
    }

    public function checkEmpruntActif(): bool
    {
        if (!isset($this->dateRetour)) {
            return true;
        } else {
            return false;
        }
    }

    public function checkEmpruntLate(): bool
    {
        if ($this->checkEmpruntActif() && (new DateTime()) > $this->dateRetourEstimee) {
            return true;
        } else {
            return false;
        }
    }
}