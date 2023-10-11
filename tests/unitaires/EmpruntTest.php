<?php

namespace App\Tests\unitaires;

use App\Entites\Adherent;
use App\Entites\BluRay;
use App\Entites\Emprunt;
use App\Entites\Livre;
use PHPUnit\Framework\TestCase;

class EmpruntTest extends TestCase
{
    /**
     * @test
     */
    public function testCheckEmpruntLate()
    {
        $adherent = new Adherent();
        $livre = new Livre();
        $emprunt = new Emprunt();
        $this->assertTrue($emprunt->checkEmpruntLate());
    }

    /**
     * @test
     */
    public function testCheckEmpruntActif()
    {
        $adherent = new Adherent();
        $bluray = new BluRay();
        $emprunt = new Emprunt();
        $this->assertTrue($emprunt->checkEmpruntActif());
    }
}
