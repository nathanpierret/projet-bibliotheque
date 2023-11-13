<?php

require_once 'bootstrap.php';
require "./vendor/autoload.php";

use App\Entites\Adherent;

$adherent = $entityManager->find("App\\Entites\\Adherent",1);

echo $adherent->getId()."\n";
echo $adherent->getNumeroAdherent()."\n";
echo $adherent->getPrenom()."\n";
echo $adherent->getNom()."\n";
echo $adherent->getEmail()."\n";
echo $adherent->getDateAdhesion()->format("d/m/Y H:i:s")."\n";