<?php

namespace App;

use App\Media;
use DateTime;

class Magazine extends Media
{
    private int $numero;
    private DateTime $datePublication;
}