<?php

require "./vendor/autoload.php";
/* @var $entityManager */
require_once "./bootstrap.php";

// Définir les commandes
use App\UserStories\ListerMedias\ListerMedias;
use Silly\Application;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\ValidatorBuilder;

$app = new Application();

$app->command('biblio:add:Livre', function (SymfonyStyle $io) use ($entityManager) {
    $io->title("Formulaire de création d'un Livre");
    $titre = $io->ask("Titre du livre (obligatoire) ");
    $isbn = $io->ask("ISBN (obligatoire) ");
    $auteur = $io->ask("Nom de l'auteur (obligatoire) ");
    $nbPages = $io->ask("Nombre de pages (obligatoire) ");
    $requete = new \App\UserStories\CreerLivre\CreerLivreRequete($titre,$isbn,$auteur,$nbPages);
    $creerLivre = new \App\UserStories\CreerLivre\CreerLivre($entityManager,(new ValidatorBuilder())->enableAnnotationMapping()->getValidator());
    try {
        $creerLivre->execute($requete);
        $io->success("Le livre a bien été implémenté dans la base de données !");
    } catch (Exception $e) {
        $io->error($e->getMessage());
    }
});

$app->command('biblio:add:Magazine', function (SymfonyStyle $io) use ($entityManager) {
    $io->title("Formulaire de création d'un Magazine");
    $titre = $io->ask("Titre du magazine (obligatoire) ");
    $numero = $io->ask("Numéro du magazine (obligatoire) ");
    $datePublication = $io->ask("Date de publication (obligatoire) ");
    $requete = new \App\UserStories\CreerMagazine\CreerMagazineRequete($titre,$numero,$datePublication);
    $creerMagazine = new \App\UserStories\CreerMagazine\CreerMagazine($entityManager,(new ValidatorBuilder())->enableAnnotationMapping()->getValidator());
    try {
        $creerMagazine->execute($requete);
        $io->success("Le magazine a bien été implémenté dans la base de données !");
    } catch (Exception $e) {
        $io->error($e->getMessage());
    }
});

$app->command('biblio:list:Media:new', function (SymfonyStyle $io) use ($entityManager) {
    $listerMedias = new ListerMedias($entityManager);
    $listeNouvMedias = $listerMedias->execute();
    $tableMedias = $io->createTable();
    $tableMedias->setHeaderTitle("Liste des nouveaux médias");
    $tableMedias->setHeaders(['id','titre','statut','dateCreation','typeMedia']);
    foreach ($listeNouvMedias as $media) {
        $tableMedias->addRow([$media['id'],$media['titre'],$media['statut'],$media['dateCreation'],$media['Type']]);
    }
    $tableMedias->render();
});

$app->run();