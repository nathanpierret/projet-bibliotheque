<?php

namespace App\Tests\Integration\UserStories;

use App\Entites\Adherent;
use App\Entites\Emprunt;
use App\Entites\Media;
use App\Services\GenerateurNumeroAdherent;
use App\Services\GenerateurNumeroEmprunt;
use App\UserStories\CreerAdherent\CreerAdherent;
use App\UserStories\CreerAdherent\CreerAdherentRequete;
use App\UserStories\CreerMagazine\CreerMagazine;
use App\UserStories\CreerMagazine\CreerMagazineRequete;
use App\UserStories\EmprunterMedia\EmprunterMedia;
use App\UserStories\EmprunterMedia\EmprunterMediaRequete;
use App\UserStories\RendreDisponibleMedia\RendreDisponibleMedia;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ValidatorBuilder;

class EmprunterMediaTest extends TestCase
{
    protected EntityManagerInterface $entityManager;
    protected GenerateurNumeroAdherent $generateurNumeroAdherent;
    protected GenerateurNumeroEmprunt $generateurNumeroEmprunt;
    protected ValidatorInterface $validateur;

    protected function setUp(): void
    {
        echo "setup ---------------------------------------------------------".PHP_EOL;
        // Configuration de Doctrine pour les tests
        $config = ORMSetup::createAttributeMetadataConfiguration(
            [__DIR__.'/../../../src/'],
            true
        );

        // Configuration de la connexion à la base de données
        // Utilisation d'une base de données SQLite en mémoire
        $connection = DriverManager::getConnection([
            'driver' => 'pdo_sqlite',
            'path' => ':memory:'
        ], $config);

        // Création des dépendances
        $this->entityManager = new EntityManager($connection, $config);
        $this->validateur = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
        $this->generateurNumeroAdherent = new GenerateurNumeroAdherent();
        $this->generateurNumeroEmprunt = new GenerateurNumeroEmprunt();

        // Création du schema de la base de données
        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->createSchema($metadata);
    }

    #[test]
    public function emprunterMedia_ValeursCorrectes_True() {
        // Arrange
        $requete = new CreerMagazineRequete("Weebdo","169","18/11/2023 16:15:15");
        $creerMagazine = new CreerMagazine($this->entityManager,$this->validateur);
        $creerMagazine->execute($requete);
        $requete2 = new CreerAdherentRequete("Lucy","Pretler","lucypretler@test.fr");
        $creerAdherent = new CreerAdherent($this->entityManager,$this->generateurNumeroAdherent,$this->validateur);
        $creerAdherent->execute($requete2);
        $repository = $this->entityManager->getRepository(Adherent::class);
        $adherent = $repository->findOneBy(['email' => 'lucypretler@test.fr']);
        $adherent->setDateAdhesion(\DateTime::createFromFormat("d/m/Y H:i:s","11/11/2023 15:14:12"));
        $this->entityManager->flush();
        $media = $this->entityManager->find(Media::class,1);
        $rendreDisponibleMedia = new RendreDisponibleMedia($this->entityManager);
        $rendreDisponibleMedia->execute($media->getId());
        $requete3 = new EmprunterMediaRequete($adherent->getNumeroAdherent(),$media->getId());
        $emprunterMedia = new EmprunterMedia($this->entityManager,$this->generateurNumeroEmprunt,$this->validateur);
        // Act
        $resultat = $emprunterMedia->execute($requete3);
        // Assert
        $this->assertTrue($resultat);
        $repository = $this->entityManager->getRepository(Emprunt::class);
        $emprunt = $repository->find(1);
        $this->assertNotNull($emprunt);
        $this->assertEquals("EM-000000001",$emprunt->getNumeroEmprunt());
        $this->assertNotNull($emprunt->getDateEmprunt());
        $this->assertNotNull($emprunt->getDateRetourEstimee());
        $this->assertEquals($adherent,$emprunt->getAdherent());
        $this->assertEquals($media,$emprunt->getMedia());
    }

    #[test]
    public function emprunterMedia_MediaNonRenseigne_Exception() {
        $requete2 = new CreerAdherentRequete("Lucy","Pretler","lucypretler@test.fr");
        $creerAdherent = new CreerAdherent($this->entityManager,$this->generateurNumeroAdherent,$this->validateur);
        $creerAdherent->execute($requete2);
        $repository = $this->entityManager->getRepository(Adherent::class);
        $adherent = $repository->findOneBy(['email' => 'lucypretler@test.fr']);
        $adherent->setDateAdhesion(\DateTime::createFromFormat("d/m/Y H:i:s","11/11/2023 15:14:12"));
        $this->entityManager->flush();
        $requete3 = new EmprunterMediaRequete($adherent->getNumeroAdherent());
        $emprunterMedia = new EmprunterMedia($this->entityManager,$this->generateurNumeroEmprunt,$this->validateur);
        $this->expectExceptionMessage("L'id du média doit être renseigné !");
        // Act
        $resultat = $emprunterMedia->execute($requete3);
    }

    #[test]
    public function emprunterMedia_AdherentNonRenseine_Exception() {
        // Arrange
        $requete = new CreerMagazineRequete("Weebdo","169","18/11/2023 16:15:15");
        $creerMagazine = new CreerMagazine($this->entityManager,$this->validateur);
        $creerMagazine->execute($requete);
        $media = $this->entityManager->find(Media::class,1);
        $rendreDisponibleMedia = new RendreDisponibleMedia($this->entityManager);
        $rendreDisponibleMedia->execute($media->getId());
        $requete3 = new EmprunterMediaRequete("",$media->getId());
        $emprunterMedia = new EmprunterMedia($this->entityManager,$this->generateurNumeroEmprunt,$this->validateur);
        $this->expectExceptionMessage("Le numéro d'adhérent doit être renseigné !");
        // Act
        $resultat = $emprunterMedia->execute($requete3);
    }

    // Tests manquants
}
