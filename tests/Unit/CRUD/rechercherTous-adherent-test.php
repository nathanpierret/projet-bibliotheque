<?php

require_once './bootstrap.php';
require "./vendor/autoload.php";

use App\Entites\Adherent;

$adherents = $entityManager->getRepository("App\\Entites\\Adherent")->findAll();

foreach ($adherents as $adherent) {
    echo $adherent->getId()."\n";
    echo $adherent->getNumeroAdherent()."\n";
    echo $adherent->getPrenom()."\n";
    echo $adherent->getNom()."\n";
    echo $adherent->getEmail()."\n";
    echo $adherent->getDateAdhesion()->format("d/m/Y H:i:s")."\n";
}