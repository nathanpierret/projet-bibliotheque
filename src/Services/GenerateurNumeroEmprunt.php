<?php

namespace App\Services;

class GenerateurNumeroEmprunt
{
    public function generer(int $id): string
    {
        $numero = sprintf("%'.06d",$id);
    }
}