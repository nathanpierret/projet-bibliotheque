<?php

namespace App\Entites;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Magazine extends Media
{
    #[ORM\Column(type: "string")]
    private int $numero;
    #[ORM\Column(type: "string")]
    private string $datePublication;

    public function __construct()
    {
    }

    public function setNumero(int $numero): void
    {
        $this->numero = $numero;
    }

    public function setDatePublication(string $datePublication): void
    {
        $this->datePublication = $datePublication;
    }

    public function getNumero(): int
    {
        return $this->numero;
    }

    public function getDatePublication(): string
    {
        return $this->datePublication;
    }

}