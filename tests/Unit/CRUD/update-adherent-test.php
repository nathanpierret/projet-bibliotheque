<?php

require_once 'bootstrap.php';
require "./vendor/autoload.php";

use App\Entites\Adherent;

$adherent = $entityManager->find("App\\Entites\\Adherent",1);
$adherent->setPrenom("Jean-Luc");
$adherent->setEmail("jeanlucgenereux@gmail.com");
$entityManager->persist($adherent);
$entityManager->flush();

$adherent2 = $entityManager->find("App\\Entites\\Adherent",1);

echo $adherent2->getId()."\n";
echo $adherent2->getNumeroAdherent()."\n";
echo $adherent2->getPrenom()."\n";
echo $adherent2->getNom()."\n";
echo $adherent2->getEmail()."\n";
echo $adherent2->getDateAdhesion()->format("d/m/Y H:i:s")."\n";