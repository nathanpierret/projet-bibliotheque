<?php

namespace App\Entites;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Livre extends Media
{
    #[ORM\Column(name: "ISBN",type: "string", length: 17)]
    private string $isbn;
    #[ORM\Column(type: "string", length: 50)]
    private string $auteur;
    #[ORM\Column(type: "integer")]
    private int $nbPages;

    public function __construct()
    {
    }

    public function setIsbn(string $isbn): void
    {
        $this->isbn = $isbn;
    }

    public function setAuteur(string $auteur): void
    {
        $this->auteur = $auteur;
    }

    public function setNbPages(int $nbPages): void
    {
        $this->nbPages = $nbPages;
    }

    public function getIsbn(): string
    {
        return $this->isbn;
    }

    public function getAuteur(): string
    {
        return $this->auteur;
    }

    public function getNbPages(): int
    {
        return $this->nbPages;
    }

}