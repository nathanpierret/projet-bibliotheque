<?php

use App\Services\GenerateurNumeroAdherent;
use App\UserStories\CreerAdherent\CreerAdherent;
use App\UserStories\CreerAdherent\CreerAdherentRequete;
use Symfony\Component\Validator\ValidatorBuilder;

require_once ".\bootstrap.php";

$generateur = new GenerateurNumeroAdherent();
$validateur = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();

$requete = new CreerAdherentRequete("Penny","Carteron","pennycarteron@test.fr");
$creerAdherent = new CreerAdherent($entityManager,$generateur,$validateur);
$creerAdherent->execute($requete);