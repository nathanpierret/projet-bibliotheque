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
}