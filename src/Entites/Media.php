<?php

namespace App\Entites;

use DateTime;

class Media
{
    protected int $id;
    protected string $titre;
    protected int $dureeEmprunt;
    protected string $statut;
    protected DateTime $dateCreation;

    public function __construct()
    {

    }

    public function setTitre(string $titre): void
    {
        $this->titre = $titre;
    }

    public function setDureeEmprunt(string $dureeEmprunt): void
    {
        $this->dureeEmprunt = $dureeEmprunt;
    }

    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }

    public function setDateCreation(DateTime $dateCreation): void
    {
        $this->dateCreation = $dateCreation;
    }

}