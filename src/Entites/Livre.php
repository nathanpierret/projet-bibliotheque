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
}