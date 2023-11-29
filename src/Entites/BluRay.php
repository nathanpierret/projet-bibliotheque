<?php

namespace App\Entites;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class BluRay extends Media
{
    #[ORM\Column(type: "string",length: 70)]
    private string $realisateur;
    #[ORM\Column(type: "integer")]
    private int $duree;
    #[ORM\Column(type: "string", length: 4)]
    private string $anneeSortie;

    public function __construct()
    {
    }

    public function setRealisateur(string $realisateur): void
    {
        $this->realisateur = $realisateur;
    }

    public function setDuree(int $duree): void
    {
        $this->duree = $duree;
    }

    public function setAnneeSortie(string $anneeSortie): void
    {
        $this->anneeSortie = $anneeSortie;
    }

    public function getRealisateur(): string
    {
        return $this->realisateur;
    }

    public function getDuree(): int
    {
        return $this->duree;
    }

    public function getAnneeSortie(): string
    {
        return $this->anneeSortie;
    }

}