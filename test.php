<?php

require_once ".\bootstrap.php";

$listerMedias = new \App\UserStories\ListerMedias\ListerMedias($entityManager);
$resultat = $listerMedias->execute();

echo $resultat[0]["dateCreation"];