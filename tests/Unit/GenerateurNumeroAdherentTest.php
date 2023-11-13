<?php

namespace App\Tests\Unit;

use App\Services\GenerateurNumeroAdherent;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class GenerateurNumeroAdherentTest extends TestCase
{
    #[test]
    public function GenerateurNumeroAdherentTest_FormatCorrect_True() {
        // Arrange
        $generateur = new GenerateurNumeroAdherent();
        $numero = $generateur->generer();
        // Assert
        $this->assertEquals(9,strlen($numero));
        $this->assertStringStartsWith("AD-",$numero);
        $this->assertLessThanOrEqual(999999,intval(substr($numero,3)));
        $this->assertGreaterThanOrEqual(0,intval(substr($numero,3)));
    }
}
