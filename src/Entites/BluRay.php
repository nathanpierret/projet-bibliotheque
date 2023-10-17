<?php

namespace App\Entites;

class BluRay extends Media
{
    private string $realisateur;
    private int $duree;
    private string $anneeSortie;

    public function __construct()
    {
    }

    public function setRealisateur(string $realisateur): void
    {
        $this->realisateur = $realisateur;
    }

    public function setDuree(int $duree): void
    {
        $this->duree = $duree;
    }

    public function setAnneeSortie(string $anneeSortie): void
    {
        $this->anneeSortie = $anneeSortie;
    }

}