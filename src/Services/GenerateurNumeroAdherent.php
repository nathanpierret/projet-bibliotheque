<?php

namespace App\Services;

//Peut faire l'objet d'un test UNITAIRE

class GenerateurNumeroAdherent
{
    public function generer(): string
    {
        $numero = sprintf("%'.06d",rand(0,999999));
        return "AD-".$numero;
    }
}