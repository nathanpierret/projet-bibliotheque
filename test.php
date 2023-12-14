<?php

require_once ".\bootstrap.php";

$repo = $entityManager->getRepository(\App\Entites\Adherent::class);

print_r($repo->findBy(["numeroAdherent" => "AD-835547"]));