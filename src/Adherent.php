<?php

namespace App;

use DateTime;

class Adherent
{
    private string $numeroAdherent;
    private string $prenom;
    private string $nom;
    private string $email;
    private DateTime $dateAdhesion;

    public function __construct()
    {
    }
}