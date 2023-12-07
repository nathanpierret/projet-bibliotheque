<?php

namespace App\Tests\Integration\UserStories;

use App\Entites\DureeEmprunt;
use App\Entites\Livre;
use App\Entites\StatutEmprunt;
use App\UserStories\CreerLivre\CreerLivre;
use App\UserStories\CreerLivre\CreerLivreRequete;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ValidatorBuilder;

class CreerLivreTest extends TestCase
{
    protected EntityManagerInterface $entityManager;
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
        $this->validateur = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();

        // Création du schema de la base de données
        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->createSchema($metadata);
    }

    #[test]
    public function creerLivre_ValeursCorrectes_True() {
        // Arrange
        $requete = new CreerLivreRequete("La noyade","978-2-1234-5680-3","Le petit GREGORY",164);
        $creerLivre = new CreerLivre($this->entityManager,$this->validateur);
        // Act
        $resultat = $creerLivre->execute($requete);
        // Assert
        $repository = $this->entityManager->getRepository(Livre::class);
        $livre = $repository->findOneBy(["isbn" => "978-2-1234-5680-3"]);
        $this->assertNotNull($livre);
        $this->assertEquals("La noyade",$livre->getTitre());
        $this->assertEquals("978-2-1234-5680-3",$livre->getIsbn());
        $this->assertEquals("Le petit GREGORY",$livre->getAuteur());
        $this->assertEquals(164,$livre->getNbPages());
        $this->assertEquals(DureeEmprunt::DUREE_EMPRUNT_LIVRE,$livre->getDureeEmprunt());
        $this->assertEquals(StatutEmprunt::STATUT_NOUVEAU,$livre->getStatut());
    }

    #[test]
    public function creerLivre_ISBNNonRenseigne_Exception() {
        // Arrange
        $requete = new CreerLivreRequete("La noyade","","Le petit GREGORY",164);
        $creerLivre = new CreerLivre($this->entityManager,$this->validateur);
        $this->expectExceptionMessage("L'ISBN doit être renseigné !");
        // Act
        $resultat = $creerLivre->execute($requete);
    }

    #[test]
    public function creerLivre_ISBNNonvalide_Exception() {
        // Arrange
        $requete = new CreerLivreRequete("La noyade","6489","Le petit GREGORY",164);
        $creerLivre = new CreerLivre($this->entityManager,$this->validateur);
        $this->expectExceptionMessage("L'ISBN n'est ni de type ISBN-10, ni de type ISBN-13 !");
        // Act
        $resultat = $creerLivre->execute($requete);
    }

    #[test]
    public function creerLivre_ISBNNonUnique_Exception() {
        // Arrange
        $requete1 = new CreerLivreRequete("La noyade","978-2-1234-5680-3","Le petit GREGORY",164);
        $creerLivre = new CreerLivre($this->entityManager,$this->validateur);
        $requete2 = new CreerLivreRequete("The Shining","978-2-1234-5680-3","Johnny",143);
        $creerLivre->execute($requete1);
        $this->expectExceptionMessage("Cet ISBN est déjà utilisé !");
        // Act
        $resultat = $creerLivre->execute($requete2);
    }

    #[test]
    public function creerLivre_TitreNonRenseigne_Exception() {
        // Arrange
        $requete = new CreerLivreRequete("","978-2-1234-5680-3","Le petit GREGORY",164);
        $creerLivre = new CreerLivre($this->entityManager,$this->validateur);
        $this->expectExceptionMessage("Le titre doit être renseigné !");
        // Act
        $resultat = $creerLivre->execute($requete);
    }

    #[test]
    public function creerLivre_NomAuteurNonRenseigne_Exception() {
        // Arrange
        $requete = new CreerLivreRequete("La noyade","978-2-1234-5680-3","",164);
        $creerLivre = new CreerLivre($this->entityManager,$this->validateur);
        $this->expectExceptionMessage("Le nom de l'auteur doit être renseigné !");
        // Act
        $resultat = $creerLivre->execute($requete);
    }

    #[test]
    public function creerLivre_NombrePagesNonRenseigne_Exception() {
        // Arrange
        $requete = new CreerLivreRequete("La noyade","978-2-1234-5680-3","Le petit GREGORY");
        $creerLivre = new CreerLivre($this->entityManager,$this->validateur);
        $this->expectExceptionMessage("Le nombre de pages doit être renseigné !");
        // Act
        $resultat = $creerLivre->execute($requete);
    }

    #[test]
    public function creerLivre_NombrePagesEgalAZero_Exception() {
        // Arrange
        $requete = new CreerLivreRequete("La noyade","978-2-1234-5680-3","Le petit GREGORY",-10);
        $creerLivre = new CreerLivre($this->entityManager,$this->validateur);
        $this->expectExceptionMessage("Le nombre de pages doit être supérieur à 0 !");
        // Act
        $resultat = $creerLivre->execute($requete);
    }
}
