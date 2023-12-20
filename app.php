<?php

require "./vendor/autoload.php";
/* @var $entityManager */
require_once "./bootstrap.php";

// Définir les commandes
use App\Entites\Emprunt;
use App\Services\GenerateurNumeroEmprunt;
use App\UserStories\EmprunterMedia\EmprunterMedia;
use App\UserStories\EmprunterMedia\EmprunterMediaRequete;
use App\UserStories\ListerMedias\ListerMedias;
use App\UserStories\RendreDisponibleMedia\RendreDisponibleMedia;
use App\UserStories\RetournerEmprunt\RetournerEmprunt;
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

$app->command('biblio:disponibility', function (SymfonyStyle $io) use ($entityManager) {
   $io->title("Formulaire pour rendre disponible un média");
   $idMedia = $io->ask("Identifiant du média à rendre disponible (obligatoire et valide)");
   $rendreDisponibleMedia = new RendreDisponibleMedia($entityManager);
    try {
        $rendreDisponibleMedia->execute($idMedia);
        $io->success("Le média choisi a bien été rendu disponible !");
    } catch (Exception $e) {
        $io->error($e->getMessage());
    }

});

$app->command('biblio:emprunt', function (SymfonyStyle $io) use ($entityManager) {
    $io->title("Formulaire pour emprunter un média");
    $idMedia = $io->ask("Identifiant du média à emprunter (obligatoire et valide)");
    $numAdherent = $io->ask("Numéro de l'adhérent qui emprunte (obligatoire et valide)");
    $emprunterMediaRequete = new EmprunterMediaRequete($numAdherent,$idMedia);
    $emprunterMedia = new EmprunterMedia($entityManager, new GenerateurNumeroEmprunt(), (new ValidatorBuilder())->enableAnnotationMapping()->getValidator());
    try {
        $emprunterMedia->execute($emprunterMediaRequete);
        $io->success("L'emprunt du média ".$idMedia." par l'adhérent ".$numAdherent." a bien été effectué !");
    } catch (Exception $e) {
        $io->error($e->getMessage());
    }
});

$app->command('biblio:return:emprunt', function (SymfonyStyle $io) use ($entityManager) {
    $io->title("Formulaire pour retourner un emprunt");
    $numeroEmprunt = $io->ask("Numéro de l'emprunt à retourner (obligatoire et valide)");
    $retournerEmprunt = new RetournerEmprunt($entityManager);
    try {
        $emprunt = $entityManager->getRepository(Emprunt::class)->findOneBy(["numeroEmprunt" => $numeroEmprunt]);
        if ($emprunt->checkEmpruntLate()) {
            $io->warning("L'emprunt a été retourné en retard !");
        }
        $retournerEmprunt->execute($numeroEmprunt);
        $io->success("L'emprunt ".$numeroEmprunt." a bien été retourné !");
    } catch (Exception $e) {
        $io->error($e->getMessage());
    }
});

$app->run();