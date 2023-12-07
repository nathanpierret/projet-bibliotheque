<?php

require_once ".\bootstrap.php";

$listerMedias = new \App\UserStories\ListerMedias\ListerMedias($entityManager);
$resultat = $listerMedias->execute();

print_r($resultat);