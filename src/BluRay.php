<?php

namespace App;

use App\Media;

class BluRay extends Media
{
    private string $realisateur;
    private int $duree;
    private string $anneeSortie;

    public function __construct()
    {
    }
}