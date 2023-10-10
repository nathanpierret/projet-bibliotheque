<?php

namespace App;

use App\Media;

class Livre extends Media
{
    private int $isbn;
    private string $auteur;
    private int $nbPages;

    public function __construct()
    {

    }
}