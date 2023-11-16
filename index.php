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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $prenom = ucfirst(strtolower($_POST["prenom-adherent"]));
    $nom = ucfirst(strtolower($_POST["nom-adherent"]));
    $email = $_POST["email-adherent"];
    $requete = new CreerAdherentRequete($prenom,$nom,$email);
    $creerAdherent = new CreerAdherent($entityManager,$generateur,$validateur);
    try {
        $creerAdherent->execute($requete);
    } catch (Exception $e) {
        $erreur = $e;
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
    <input type="text" name="prenom-adherent" id="prenom-adherent">

    <label for="nom-adherent">Nom</label>
    <input type="text" name="nom-adherent" id="nom-adherent">

    <label for="email-adherent">Email</label>
    <input type="text" name="email-adherent" id="email-adherent">

    <input type="submit" name="creer-adherent" value="Ajouter">

    <?php if (isset($erreur)) { ?>
        <div class="erreur"><?= $erreur->getMessage()?></div>
    <?php } ?>
</form>
</body>
</html>