<?php

namespace App\UserStories\CreerMagazine;

use Symfony\Component\Validator\Constraints as Assert;

class CreerMagazineRequete
{
    #[Assert\NotBlank(
        message: "Le titre doit être renseigné !"
    )]
    public string $titre;
    #[Assert\NotBlank(
        message: "La date de parution doit être renseignée !"
    )]
    public string $dateCreation;
    #[Assert\NotBlank(
        message: "Le numéro de magazine doit être renseigné !"
    )]
    public string $numero;
    #[Assert\NotBlank(
        message: "La date de publication doit être renseignée !"
    )]
    public string $datePublication;

    /**
     * @param string $titre
     * @param string $dateCreation
     * @param string $numero
     * @param string $datePublication
     */
    public function __construct(string $titre, string $dateCreation, string $numero, string $datePublication)
    {
        $this->titre = $titre;
        $this->dateCreation = $dateCreation;
        $this->numero = $numero;
        $this->datePublication = $datePublication;
    }


}