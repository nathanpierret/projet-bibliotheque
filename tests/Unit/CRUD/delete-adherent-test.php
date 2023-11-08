<?php

require_once './bootstrap.php';
require "./vendor/autoload.php";

use App\Entites\Adherent;

$adherent = $entityManager->find("App\\Entites\\Adherent",2);

$entityManager->remove($adherent);
$entityManager->flush();