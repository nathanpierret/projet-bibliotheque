<?php

namespace App\Entites;

use DateTime;

class Emprunt
{
    private int $id;
    private DateTime $dateEmprunt;
    private ?DateTime $dateRetourEstimee;
    private ?DateTime $dateRetour;
    private Adherent $adherent;
    private Media $media;

    public function __construct()
    {
        $this->dateRetourEstimee = null;
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
        if ($this->checkEmpruntActif() and (new DateTime()) > $this->dateRetourEstimee) {
            return true;
        } else {
            return false;
        }
    }
}