<?php

namespace App;

use DateTime;

class Media
{
    protected int $id;
    protected string $titre;
    protected string $dureeEmprunt;
    protected string $statut;
    protected DateTime $dateCreation;

    public function __construct()
    {

    }
}