<?php
use App\UserStories\CreerAdherent\CreerAdherentRequete;
use App\UserStories\CreerAdherent\CreerAdherent;
use App\Services\GenerateurNumeroAdherent;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\ValidatorBuilder;

require_once ".\bootstrap.php";

$generateur = new GenerateurNumeroAdherent();
$validateur = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
$erreur = null;
$prenom = null;
$nom = null;
$email = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $prenom = ucfirst(strtolower($_POST["prenom-adherent"]));
    $nom = ucfirst(strtolower($_POST["nom-adherent"]));
    $email = $_POST["email-adherent"];
    $requete = new CreerAdherentRequete($prenom,$nom,$email);
    $creerAdherent = new CreerAdherent($entityManager,$generateur,$validateur);
    try {
        $creerAdherent->execute($requete);
    } catch (Exception $e) {
        if (str_contains($e->getMessage(),"Le prénom est obligatoire !")) {
            $prenomErreur = "Le prénom est obligatoire !";
        }
        if (str_contains($e->getMessage(),"Le nom est obligatoire !")) {
            $nomErreur = "Le nom est obligatoire !";
        }
        if (str_contains($e->getMessage(),"L'email est obligatoire !")) {
            $emailErreur1 = "L'email est obligatoire !";
        }
        if (str_contains($e->getMessage(),"L'email \"".$_POST["email-adherent"]."\" est incorrect !")) {
            $emailErreur2 = "L'email \"".$_POST["email-adherent"]."\" est incorrect !";
        }
    }
}
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Projet Bibliothèque</title>
</head>
<body>
<h1>Création d'un adhérent</h1>

<form method="post">
    <label for="prenom-adherent">Prénom</label>
    <input type="text" name="prenom-adherent" id="prenom-adherent" value="<?= $prenom?>">

    <?php if (isset($prenomErreur)) {?>
        <div class="erreur"><?= $prenomErreur?></div>
    <?php } ?>

    <label for="nom-adherent">Nom</label>
    <input type="text" name="nom-adherent" id="nom-adherent" value="<?= $nom?>">

    <?php if (isset($nomErreur)) {?>
        <div class="erreur"><?= $nomErreur?></div>
    <?php } ?>

    <label for="email-adherent">Email</label>
    <input type="text" name="email-adherent" id="email-adherent" value="<?= $email?>">

    <?php if (isset($emailErreur1)) {?>
        <div class="erreur"><?= $emailErreur1?></div>
    <?php } ?>

    <?php if (isset($emailErreur2)) {?>
        <div class="erreur"><?= $emailErreur2?></div>
    <?php } ?>

    <input type="submit" name="creer-adherent" value="Ajouter">
</form>
</body>
</html>