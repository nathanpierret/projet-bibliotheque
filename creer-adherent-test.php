<?php

$entityManager = require_once join(DIRECTORY_SEPARATOR, [__DIR__, 'bootstrap.php']);
require "./vendor/autoload.php";
require "./tests/utils/couleurs.php";

use App\Entites\Adherent;

$adherent = new Adherent();
$adherent->setNumeroAdherent();
$adherent->setPrenom("Jean-Marc");
$adherent->setNom("Généreux");
$adherent->setEmail("jeanmarcgenereux@gmail.com");
$adherent->setDateAdhesion(new DateTime());

$entityManager->persist($adherent);
$entityManager->flush();

echo PHP_EOL;
echo GREEN_BACKGROUND.BLACK;
echo "Tests : classe Adherent";
echo RESET;
echo PHP_EOL;

echo "Test : création d'un adhérent dans la BDD \n";

