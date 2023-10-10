<?php

namespace App;

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
}