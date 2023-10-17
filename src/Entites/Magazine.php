<?php

namespace App\Entites;

use DateTime;

class Magazine extends Media
{
    private int $numero;
    private DateTime $datePublication;

    public function __construct()
    {
    }

    public function setNumero(int $numero): void
    {
        $this->numero = $numero;
    }

    public function setDatePublication(DateTime $datePublication): void
    {
        $this->datePublication = $datePublication;
    }

}