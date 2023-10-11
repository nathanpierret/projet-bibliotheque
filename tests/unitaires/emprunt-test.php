<?php

use App\Adherent;
use App\BluRay;
use App\Emprunt;
use App\Livre;
use App\Magazine;

require "./vendor/autoload.php";
require "./tests/utils/couleurs.php";

echo PHP_EOL;
echo GREEN_BACKGROUND.BLACK;
echo "Tests : classe Emprunt";
echo RESET;
echo PHP_EOL;

echo "Test : vérification qu'un emprunt est toujours en cours (date de retour non précisée)\n";
//Assertion
$emprunt1 = new Emprunt();
//Act
$resultat = $emprunt1->checkEmpruntActif();
//Assertion
if ($resultat) {
    echo GREEN."Test OK".RESET.PHP_EOL;
} else {
    echo RED."Test pas OK".RESET.PHP_EOL;
}

echo "Test : vérification qu'un emprunt est en alerte (date de retour non précisée et dépassement de date de retour estimée)\n";
$livre2 = new Livre();
$emprunt2 = new Emprunt();
//Arrange
$emprunt2->setDateEmprunt("01/09/2023");
//Act
$resultat = $emprunt2->checkEmpruntLate();
//Assertion
if ($resultat) {
    echo GREEN."Test OK".RESET.PHP_EOL;
} else {
    echo RED."Test pas OK".RESET.PHP_EOL;
}