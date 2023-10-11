<?php

namespace App\Entites;

use DateTime;

class Magazine extends Media
{
    private int $numero;
    private DateTime $datePublication;
}