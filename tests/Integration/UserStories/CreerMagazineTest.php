<?php

namespace App\Tests\Integration\UserStories;

use App\Entites\Magazine;
use App\UserStories\CreerMagazine\CreerMagazine;
use App\UserStories\CreerMagazine\CreerMagazineRequete;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ValidatorBuilder;

class CreerMagazineTest extends TestCase
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
    public function creerMagazine_ValeursCorrectes_True() {
        // Arrange
        $requete = new CreerMagazineRequete("Weebdo","169","18/11/2023 16:15:15");
        $creerMagazine = new CreerMagazine($this->entityManager,$this->validateur);
        // Act
        $resultat = $creerMagazine->execute($requete);
        // Assert
        $repository = $this->entityManager->getRepository(Magazine::class);
        $magazine = $repository->findOneBy(["datePublication" => "18/11/2023 16:15:15"]);
        $this->assertNotNull($magazine);
        $this->assertEquals("Weebdo",$magazine->getTitre());
        $this->assertEquals("169",$magazine->getNumero());
        $this->assertEquals("18/11/2023 16:15:15",$magazine->getDatePublication());
        $this->assertEquals(10,$magazine->getDureeEmprunt());
        $this->assertEquals("Nouveau",$magazine->getStatut());
    }

    #[test]
    public function creerMagazine_TitreNonRenseigne_Exception() {
        // Arrange
        $requete = new CreerMagazineRequete("","169","18/11/2023 16:15:15");
        $creerMagazine = new CreerMagazine($this->entityManager,$this->validateur);
        $this->expectExceptionMessage("Le titre doit être renseigné !");
        // Act
        $resultat = $creerMagazine->execute($requete);
    }

    #[test]
    public function creerMagazine_NumeroNonRenseigne_Exception() {
        // Arrange
        $requete = new CreerMagazineRequete("Weebdo","","18/11/2023 16:15:15");
        $creerMagazine = new CreerMagazine($this->entityManager,$this->validateur);
        $this->expectExceptionMessage("Le numéro de magazine doit être renseigné !");
        // Act
        $resultat = $creerMagazine->execute($requete);
    }

    #[test]
    public function creerMagazine_DatePublicationNonRenseignee_Exception() {
        // Arrange
        $requete = new CreerMagazineRequete("Weebdo","169","");
        $creerMagazine = new CreerMagazine($this->entityManager,$this->validateur);
        $this->expectExceptionMessage("La date de publication doit être renseignée !");
        // Act
        $resultat = $creerMagazine->execute($requete);
    }
}
