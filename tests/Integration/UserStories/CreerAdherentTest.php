<?php

namespace App\Tests\Integration\UserStories;

use App\Entites\Adherent;
use App\Services\GenerateurNumeroAdherent;
use App\UserStories\CreerAdherent\CreerAdherent;
use App\UserStories\CreerAdherent\CreerAdherentRequete;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ValidatorBuilder;

class CreerAdherentTest extends TestCase
{
    protected EntityManagerInterface $entityManager;
    protected GenerateurNumeroAdherent $generateur;
    protected ValidatorInterface $validateur;

    protected function setUp() : void
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
        $this->generateur = new GenerateurNumeroAdherent();
        $this->validateur = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();

        // Création du schema de la base de données
        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->createSchema($metadata);
    }

    #[test]
    public function creerAdherent_ValeursCorrectes_True() {
        // Arrange
        $requete = new CreerAdherentRequete("Lucy","Pretler","lucypretler@test.fr");
        $creerAdherent = new CreerAdherent($this->entityManager,$this->generateur,$this->validateur);
        // Act
        $resultat = $creerAdherent->execute($requete);
        // Assert
        $repository = $this->entityManager->getRepository(Adherent::class);
        $adherent = $repository->findOneBy(['email' => 'lucypretler@test.fr']);
        $this->assertNotNull($adherent);
        $this->assertEquals("Lucy",$adherent->getPrenom());
        $this->assertEquals("Pretler",$adherent->getNom());
    }

    #[test]
    public function creerAdherent_EmailNonRenseigne_Exception() {
        // Arrange
        $requete = new CreerAdherentRequete("Lucy","Pretler","");
        $creerAdherent = new CreerAdherent($this->entityManager,$this->generateur,$this->validateur);
        $this->expectExceptionMessage("L'email est obligatoire !");
        // Act
        $resultat = $creerAdherent->execute($requete);
    }

    #[test]
    public function creerAdherent_EmailInvalide_Exception() {
        // Arrange
        $requete = new CreerAdherentRequete("Lucy","Pretler","lucy.fr");
        $creerAdherent = new CreerAdherent($this->entityManager,$this->generateur,$this->validateur);
        $this->expectExceptionMessage("L'email \"lucy.fr\" est incorrect !");
        // Act
        $resultat = $creerAdherent->execute($requete);
    }

    #[test]
    public function creerAdherent_EmailNonUnique_Exception() {
        // Arrange
        $requete = new CreerAdherentRequete("Lucy","Pretler","lucypretler@test.fr");
        $creerAdherent = new CreerAdherent($this->entityManager,$this->generateur,$this->validateur);
        $test = $creerAdherent->execute($requete);
        $requete2 = new CreerAdherentRequete("Danny","Mortefau","lucypretler@test.fr");
        $creerAdherent2 = new CreerAdherent($this->entityManager,$this->generateur,$this->validateur);
        $this->expectExceptionMessage("Cette adresse mail est déjà utilisée !");
        // Act
        $resultat = $creerAdherent2->execute($requete2);
    }

    #[test]
    public function creerAdherent_PrenomNonRenseigne_Exception() {
        // Arrange
        $requete = new CreerAdherentRequete("","Pretler","lucypretler@test.fr");
        $creerAdherent = new CreerAdherent($this->entityManager,$this->generateur,$this->validateur);
        $this->expectExceptionMessage("Le prénom est obligatoire !");
        // Act
        $resultat = $creerAdherent->execute($requete);
    }

    #[test]
    public function creerAdherent_NomNonRenseigne_Exception() {
        // Arrange
        $requete = new CreerAdherentRequete("Lucy","","lucypretler@test.fr");
        $creerAdherent = new CreerAdherent($this->entityManager,$this->generateur,$this->validateur);
        $this->expectExceptionMessage("Le nom est obligatoire !");
        // Act
        $resultat = $creerAdherent->execute($requete);
    }

    #[test]
    public function creerAdherent_NumeroAdherentNonUnique_Exception() {
        // Arrange
        $requete = new CreerAdherentRequete("Lucy","Pretler","lucypretler@test.fr");
        $generateurMock = $this->createMock(GenerateurNumeroAdherent::class);
        $generateurMock->method("generer")->willReturn("AD-999999");
        $creerAdherent = new CreerAdherent($this->entityManager,$generateurMock,$this->validateur);
        $test = $creerAdherent->execute($requete);
        $requete2 = new CreerAdherentRequete("Danny","Mortefau","dannymortefau@test.fr");
        $creerAdherent2 = new CreerAdherent($this->entityManager,$generateurMock,$this->validateur);
        $this->expectExceptionMessage("Ce numéro d'adhérent est déjà utilisé ! Ce n'est pas votre jour de chance ! :(");
        // Act
        $resultat = $creerAdherent2->execute($requete2);
    }
}
