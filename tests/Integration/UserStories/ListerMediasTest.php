<?php

namespace App\Tests\Integration\UserStories;

use App\Entites\Livre;
use App\Entites\Media;
use App\Entites\StatutEmprunt;
use App\UserStories\CreerLivre\CreerLivre;
use App\UserStories\CreerLivre\CreerLivreRequete;
use App\UserStories\CreerMagazine\CreerMagazine;
use App\UserStories\CreerMagazine\CreerMagazineRequete;
use App\UserStories\ListerMedias\ListerMedias;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ValidatorBuilder;

class ListerMediasTest extends TestCase
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
    public function ListerMedias_TriOrdreDecroissant_Array () {
        // Arrange
        $requeteLivre = new CreerLivreRequete("Mon livre 4","2-1234-5680-2","Quelqun",168);
        $creerLivre = new CreerLivre($this->entityManager,$this->validateur);
        $requeteMagazine = new CreerMagazineRequete("Mon magazine 3","184","11/11/2023");
        $creerMagazine = new CreerMagazine($this->entityManager,$this->validateur);
        $listerMedias = new ListerMedias($this->entityManager);
        $mediaPrecedent = null;
        // Act
        $creerLivre->execute($requeteLivre);
        /*$livre = $this->entityManager->getRepository(Media::class)->findOneBy(["id" => 1]);
        $livre->setDateCreation("21/11/2023");
        $this->entityManager->flush();*/
        $creerMagazine->execute($requeteMagazine);
        $resultat = $listerMedias->execute();
        // Assert
        foreach ($resultat as $media) {
            if ($media == $resultat[0]) {
                $mediaPrecedent = $media;
            } else {
                $this->assertLessThanOrEqual((\DateTime::createFromFormat("d/m/Y",$media["dateCreation"])),(\DateTime::createFromFormat("d/m/Y",$mediaPrecedent["dateCreation"])));
            }
        }
    }

    #[test]
    public function ListerMedias_MediasNouveaux_Array () {
        // Arrange
        $requeteLivre = new CreerLivreRequete("Mon livre 4","2-1234-5680-2","Quelqun",168);
        $creerLivre = new CreerLivre($this->entityManager,$this->validateur);
        $requeteMagazine = new CreerMagazineRequete("Mon magazine 3","184","11/11/2023");
        $creerMagazine = new CreerMagazine($this->entityManager,$this->validateur);
        $listerMedias = new ListerMedias($this->entityManager);
        $mediaPrecedent = null;
        // Act
        $creerLivre->execute($requeteLivre);
        $livre = $this->entityManager->getRepository(Media::class)->findOneBy(["id" => 1]);
        $livre->setStatut(StatutEmprunt::STATUT_DISPONIBLE);
        $this->entityManager->flush();
        $creerMagazine->execute($requeteMagazine);
        $resultat = $listerMedias->execute();
        // Assert
        foreach ($resultat as $media) {
            $this->assertEquals(StatutEmprunt::STATUT_NOUVEAU,$media["statut"]);
        }
    }
}
