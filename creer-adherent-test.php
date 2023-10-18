<?php

require_once join(DIRECTORY_SEPARATOR, [__DIR__, 'bootstrap.php']);
require "./vendor/autoload.php";

use App\Entites\Adherent;

$adherent = new Adherent();
$adherent->setNumeroAdherent();
$adherent->setPrenom("Jean-Marc");
$adherent->setNom("Généreux");
$adherent->setEmail("jeanmarcgenereux@gmail.com");
$adherent->setDateAdhesion(new DateTime());

$entityManager->persist($adherent);
$entityManager->flush();