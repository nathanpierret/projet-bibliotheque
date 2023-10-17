<?php

namespace App\Entites;

class Livre extends Media
{
    private int $isbn;
    private string $auteur;
    private int $nbPages;

    public function __construct()
    {

    }

    public function setIsbn(int $isbn): void
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

}