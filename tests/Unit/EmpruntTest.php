<?php

namespace App\Tests\Unit;

use App\Entites\Adherent;
use App\Entites\BluRay;
use App\Entites\Emprunt;
use App\Entites\Livre;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\DateTime;

class EmpruntTest extends TestCase
{
    #[test]
    public function CheckEmpruntLate_DateRetourEstimeeInferieureADateDuJour_True()
    {
        $adherent = new Adherent();
        $livre = new Livre();
        $emprunt = new Emprunt();
        $this->assertTrue($emprunt->checkEmpruntLate());
    }

    #[test]
    public function CheckEmpruntLate_DateRetourEstimeeInferieureADateDuJour_False()
    {
        $adherent = new Adherent();
        $livre = new Livre();
        $emprunt = new Emprunt();
        $emprunt->setDateRetourEstimee(\DateTime::createFromFormat("d/m/Y H:i:s","09/11/2023 10:00:00"));
        $this->assertFalse($emprunt->checkEmpruntLate());
    }

    #[test]
    public function CheckEmpruntActif_DateRetourNonRenseignee_True()
    {
        $adherent = new Adherent();
        $bluray = new BluRay();
        $emprunt = new Emprunt();
        $this->assertTrue($emprunt->checkEmpruntActif());
    }

    #[test]
    public function CheckEmpruntActif_DateRetourNonRenseignee_False()
    {
        $adherent = new Adherent();
        $bluray = new BluRay();
        $emprunt = new Emprunt();
        $emprunt->setDateRetour(new \DateTime());
        $this->assertFalse($emprunt->checkEmpruntActif());
    }
}
