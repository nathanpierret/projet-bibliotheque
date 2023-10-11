<?php

namespace App\Entites;

use DateTime;

class Emprunt
{
    private int $id;
    private DateTime $dateEmprunt;
    private DateTime $dateRetourEstimee;
    private ?DateTime $dateRetour;
    private Adherent $adherent;
    private Media $media;

    public function __construct()
    {
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
        if ($this->checkEmpruntActif()) {
            if ((new DateTime()) > $this->dateRetourEstimee) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}