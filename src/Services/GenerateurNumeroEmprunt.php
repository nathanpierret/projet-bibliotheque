<?php

namespace App\Services;

class GenerateurNumeroEmprunt
{
    public function generer(int $id): string
    {
        $numero = sprintf("%'.09d",$id+1);
        return "EM-".$numero;
    }
}