<?php

namespace App\Tests\Integration\UserStories;

use App\Entites\Adherent;
use App\Entites\DureeEmprunt;
use App\Entites\Emprunt;
use App\Entites\Livre;
use App\Entites\StatutEmprunt;
use App\Services\GenerateurNumeroAdherent;
use App\Services\GenerateurNumeroEmprunt;
use App\UserStories\RetournerEmprunt\RetournerEmprunt;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ValidatorBuilder;

class RetournerEmpruntTest extends TestCase
{
    protected EntityManagerInterface $entityManager;
    protected GenerateurNumeroEmprunt $generateurNumeroEmprunt;
    protected GenerateurNumeroAdherent $generateurNumeroAdherent;
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
        $this->generateurNumeroEmprunt = new GenerateurNumeroEmprunt();
        $this->generateurNumeroAdherent = new GenerateurNumeroAdherent();

        // Création du schema de la base de données
        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->createSchema($metadata);
    }

    #[test]
    public function retournerEmprunt_ValeursCorrectes_True() {
        // Arrange
        $livre = new Livre();
        $livre->setIsbn("978-3-1614-8410-0");
        $livre->setTitre("Le Petit Prince");
        $livre->setAuteur("Saint Exupéry");
        $livre->setNbPages(93);
        $livre->setDateCreation((new \DateTime())->format("d/m/Y H:i:s"));
        $livre->setStatut(StatutEmprunt::STATUT_DISPONIBLE);
        $livre->setDureeEmprunt(DureeEmprunt::DUREE_EMPRUNT_LIVRE);
        // Enregistrer le livre en base de données
        $this->entityManager->persist($livre);
        $adherent = new Adherent();
        $adherent->setNumeroAdherent($this->generateurNumeroAdherent->generer());
        $adherent->setPrenom("Lucy");
        $adherent->setNom("Pretler");
        $adherent->setEmail("lucypretler@gmail.com");
        $adherent->setDateAdhesion(new \DateTime());
        // Enregistrer l'adhérent en base de données
        $this->entityManager->persist($adherent);
        $lastId = $this->entityManager->getConnection()->executeQuery("SELECT MAX(id) FROM emprunt")->fetchOne();
        if (is_null($lastId)) {
            $lastId = 0;
        }
        $emprunt = new Emprunt();
        $emprunt->setNumeroEmprunt($this->generateurNumeroEmprunt->generer($lastId));
        $emprunt->setDateEmprunt(new \DateTime());
        $emprunt->setDateRetourEstimee((clone $emprunt->getDateEmprunt())->add(\DateInterval::createFromDateString("+" . $livre->getDureeEmprunt() . "days")));
        $emprunt->setAdherent($adherent);
        $emprunt->setMedia($livre);
        $livre->setStatut(StatutEmprunt::STATUT_EMPRUNTE);
        $this->entityManager->persist($emprunt);
        $this->entityManager->flush();
        $retournerEmprunt = new RetournerEmprunt($this->entityManager);
        // Act
        $resultat = $retournerEmprunt->execute($emprunt->getNumeroEmprunt());
        $date = $emprunt->getDateRetour();
        // Assert
        $this->assertTrue($resultat);
        $this->assertEquals(StatutEmprunt::STATUT_DISPONIBLE,$livre->getStatut());
        $this->assertNotNull($emprunt->getDateRetour());
        $this->assertEquals($date,$emprunt->getDateRetour());
    }

    #[test]
    public function retournerEmprunt_EmpruntNonRenseigne_Exception() {
        // Arrange
        $livre = new Livre();
        $livre->setIsbn("978-3-1614-8410-0");
        $livre->setTitre("Le Petit Prince");
        $livre->setAuteur("Saint Exupéry");
        $livre->setNbPages(93);
        $livre->setDateCreation((new \DateTime())->format("d/m/Y H:i:s"));
        $livre->setStatut(StatutEmprunt::STATUT_DISPONIBLE);
        $livre->setDureeEmprunt(DureeEmprunt::DUREE_EMPRUNT_LIVRE);
        // Enregistrer le livre en base de données
        $this->entityManager->persist($livre);
        $adherent = new Adherent();
        $adherent->setNumeroAdherent($this->generateurNumeroAdherent->generer());
        $adherent->setPrenom("Lucy");
        $adherent->setNom("Pretler");
        $adherent->setEmail("lucypretler@gmail.com");
        $adherent->setDateAdhesion(new \DateTime());
        // Enregistrer l'adhérent en base de données
        $this->entityManager->persist($adherent);
        $lastId = $this->entityManager->getConnection()->executeQuery("SELECT MAX(id) FROM emprunt")->fetchOne();
        if (is_null($lastId)) {
            $lastId = 0;
        }
        $emprunt = new Emprunt();
        $emprunt->setNumeroEmprunt($this->generateurNumeroEmprunt->generer($lastId));
        $emprunt->setDateEmprunt(new \DateTime());
        $emprunt->setDateRetourEstimee((clone $emprunt->getDateEmprunt())->add(\DateInterval::createFromDateString("+" . $livre->getDureeEmprunt() . "days")));
        $emprunt->setAdherent($adherent);
        $emprunt->setMedia($livre);
        $livre->setStatut(StatutEmprunt::STATUT_EMPRUNTE);
        $this->entityManager->persist($emprunt);
        $this->entityManager->flush();
        $retournerEmprunt = new RetournerEmprunt($this->entityManager);
        $this->expectExceptionMessage("Le numéro d'emprunt est obligatoire !");
        // Act
        $resultat = $retournerEmprunt->execute();
    }

    #[test]
    public function retournerEmprunt_NumeroNonValide_Exception() {
        // Arrange
        $livre = new Livre();
        $livre->setIsbn("978-3-1614-8410-0");
        $livre->setTitre("Le Petit Prince");
        $livre->setAuteur("Saint Exupéry");
        $livre->setNbPages(93);
        $livre->setDateCreation((new \DateTime())->format("d/m/Y H:i:s"));
        $livre->setStatut(StatutEmprunt::STATUT_DISPONIBLE);
        $livre->setDureeEmprunt(DureeEmprunt::DUREE_EMPRUNT_LIVRE);
        // Enregistrer le livre en base de données
        $this->entityManager->persist($livre);
        $adherent = new Adherent();
        $adherent->setNumeroAdherent($this->generateurNumeroAdherent->generer());
        $adherent->setPrenom("Lucy");
        $adherent->setNom("Pretler");
        $adherent->setEmail("lucypretler@gmail.com");
        $adherent->setDateAdhesion(new \DateTime());
        // Enregistrer l'adhérent en base de données
        $this->entityManager->persist($adherent);
        $lastId = $this->entityManager->getConnection()->executeQuery("SELECT MAX(id) FROM emprunt")->fetchOne();
        if (is_null($lastId)) {
            $lastId = 0;
        }
        $emprunt = new Emprunt();
        $emprunt->setNumeroEmprunt($this->generateurNumeroEmprunt->generer($lastId));
        $emprunt->setDateEmprunt(new \DateTime());
        $emprunt->setDateRetourEstimee((clone $emprunt->getDateEmprunt())->add(\DateInterval::createFromDateString("+" . $livre->getDureeEmprunt() . "days")));
        $emprunt->setAdherent($adherent);
        $emprunt->setMedia($livre);
        $livre->setStatut(StatutEmprunt::STATUT_EMPRUNTE);
        $this->entityManager->persist($emprunt);
        $this->entityManager->flush();
        $retournerEmprunt = new RetournerEmprunt($this->entityManager);
        $this->expectExceptionMessage("Le numéro d'emprunt n'est pas valide !");
        // Act
        $resultat = $retournerEmprunt->execute("toto");
    }

    #[test]
    public function retournerEmprunt_EmpruntInexistant_Exception() {
        // Arrange
        $livre = new Livre();
        $livre->setIsbn("978-3-1614-8410-0");
        $livre->setTitre("Le Petit Prince");
        $livre->setAuteur("Saint Exupéry");
        $livre->setNbPages(93);
        $livre->setDateCreation((new \DateTime())->format("d/m/Y H:i:s"));
        $livre->setStatut(StatutEmprunt::STATUT_DISPONIBLE);
        $livre->setDureeEmprunt(DureeEmprunt::DUREE_EMPRUNT_LIVRE);
        // Enregistrer le livre en base de données
        $this->entityManager->persist($livre);
        $adherent = new Adherent();
        $adherent->setNumeroAdherent($this->generateurNumeroAdherent->generer());
        $adherent->setPrenom("Lucy");
        $adherent->setNom("Pretler");
        $adherent->setEmail("lucypretler@gmail.com");
        $adherent->setDateAdhesion(new \DateTime());
        // Enregistrer l'adhérent en base de données
        $this->entityManager->persist($adherent);
        $this->entityManager->flush();
        $retournerEmprunt = new RetournerEmprunt($this->entityManager);
        $this->expectExceptionMessage("L'emprunt choisi n'existe pas !");
        // Act
        $resultat = $retournerEmprunt->execute("EM-000000001");
    }

    #[test]
    public function retournerEmprunt_EmpruntDejaRestitue_Exception() {
        // Arrange
        $livre = new Livre();
        $livre->setIsbn("978-3-1614-8410-0");
        $livre->setTitre("Le Petit Prince");
        $livre->setAuteur("Saint Exupéry");
        $livre->setNbPages(93);
        $livre->setDateCreation((new \DateTime())->format("d/m/Y H:i:s"));
        $livre->setStatut(StatutEmprunt::STATUT_DISPONIBLE);
        $livre->setDureeEmprunt(DureeEmprunt::DUREE_EMPRUNT_LIVRE);
        // Enregistrer le livre en base de données
        $this->entityManager->persist($livre);
        $adherent = new Adherent();
        $adherent->setNumeroAdherent($this->generateurNumeroAdherent->generer());
        $adherent->setPrenom("Lucy");
        $adherent->setNom("Pretler");
        $adherent->setEmail("lucypretler@gmail.com");
        $adherent->setDateAdhesion(new \DateTime());
        // Enregistrer l'adhérent en base de données
        $this->entityManager->persist($adherent);
        $lastId = $this->entityManager->getConnection()->executeQuery("SELECT MAX(id) FROM emprunt")->fetchOne();
        if (is_null($lastId)) {
            $lastId = 0;
        }
        $emprunt = new Emprunt();
        $emprunt->setNumeroEmprunt($this->generateurNumeroEmprunt->generer($lastId));
        $emprunt->setDateEmprunt(new \DateTime());
        $emprunt->setDateRetourEstimee((clone $emprunt->getDateEmprunt())->add(\DateInterval::createFromDateString("+" . $livre->getDureeEmprunt() . "days")));
        $emprunt->setDateRetour((clone $emprunt->getDateEmprunt())->add(\DateInterval::createFromDateString("+ 3 days")));
        $emprunt->setAdherent($adherent);
        $emprunt->setMedia($livre);
        $this->entityManager->persist($emprunt);
        $this->entityManager->flush();
        $retournerEmprunt = new RetournerEmprunt($this->entityManager);
        $this->expectExceptionMessage("Cet emprunt a déjà été restitué !");
        // Act
        $resultat = $retournerEmprunt->execute("EM-000000001");
    }
}
