<?php

namespace App\Tests\Integration\UserStories;

use App\Entites\Media;
use App\Entites\StatutEmprunt;
use App\UserStories\CreerLivre\CreerLivre;
use App\UserStories\CreerLivre\CreerLivreRequete;
use App\UserStories\CreerMagazine\CreerMagazine;
use App\UserStories\CreerMagazine\CreerMagazineRequete;
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

class RendreDisponibleMediaTest extends TestCase
{
    protected EntityManagerInterface $entityManager;
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

        // Création du schema de la base de données
        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->createSchema($metadata);
    }

    #[test]
    public function RendreDisponibleMedia_ValeursCorrectes_True() {
        // Arrange
        $requeteLivre = new CreerLivreRequete("Mon livre 4","2-1234-5680-2","Quelqun",168);
        $creerLivre = new CreerLivre($this->entityManager,$this->validateur);
        $creerLivre->execute($requeteLivre);
        $rendreDisponibleMedia = new RendreDisponibleMedia($this->entityManager);
        $repo = $this->entityManager->getRepository(Media::class);
        $livre = $repo->find(1);
        // Act
        $resultat = $rendreDisponibleMedia->execute(1);
        // Assert
        $this->assertTrue($resultat);
        $this->assertEquals(StatutEmprunt::STATUT_DISPONIBLE,$livre->getStatut());
    }

    #[test]
    public function RendreDisponibleMedia_IdNonRenseigne_Exception() {
        // Arrange
        $requeteLivre = new CreerLivreRequete("Mon livre 4","2-1234-5680-2","Quelqun",168);
        $creerLivre = new CreerLivre($this->entityManager,$this->validateur);
        $creerLivre->execute($requeteLivre);
        $rendreDisponibleMedia = new RendreDisponibleMedia($this->entityManager);
        $this->expectExceptionMessage("L'ID du média est obligatoire !");
        // Act
        $resultat = $rendreDisponibleMedia->execute();
    }

    #[test]
    public function RendreDisponibleMedia_MediaNonExistant_Exception() {
        // Arrange
        $rendreDisponibleMedia = new RendreDisponibleMedia($this->entityManager);
        $repo = $this->entityManager->getRepository(Media::class);
        $media = $repo->find(1);
        $this->expectExceptionMessage("Le média avec cet identifiant n'existe pas dans la Base de Données !");
        // Act
        $resultat = $rendreDisponibleMedia->execute(1);
    }

    #[test]
    public function RendreDisponibleMedia_PasNouveauMedia_Exception() {
        // Arrange
        $requeteLivre = new CreerLivreRequete("Mon livre 4","2-1234-5680-2","Quelqun",168);
        $creerLivre = new CreerLivre($this->entityManager,$this->validateur);
        $creerLivre->execute($requeteLivre);
        $rendreDisponibleMedia = new RendreDisponibleMedia($this->entityManager);
        $repo = $this->entityManager->getRepository(Media::class);
        $livre = $repo->find(1);
        $livre->setStatut(StatutEmprunt::STATUT_DISPONIBLE);
        $this->expectExceptionMessage("Le média choisi ne possède pas le statut 'Nouveau' !");
        // Act
        $resultat = $rendreDisponibleMedia->execute(1);
    }
}
