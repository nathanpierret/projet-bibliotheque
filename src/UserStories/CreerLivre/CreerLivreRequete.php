<?php

namespace App\UserStories\CreerLivre;

use Symfony\Component\Validator\Constraints as Assert;

class CreerLivreRequete
{
    #[Assert\NotBlank(
        message: "Le titre doit être renseigné !"
    )]
    public ?string $titre;
    #[Assert\NotBlank(
        message: "L'ISBN doit être renseigné !"
    )]
    public ?string $isbn;
    #[Assert\NotBlank(
        message: "Le nom de l'auteur doit être renseigné !"
    )]
    public ?string $auteur;
    #[Assert\NotBlank(
        message: "La date de parution doit être renseignée !"
    )]
    public ?string $dateCreation;
    #[Assert\NotBlank(
        message: "Le nombre de pages doit être renseigné !"
    )]
    #[Assert\GreaterThan(
        value: 0,message: "Le nombre de pages doit être supérieur à 0 !"
    )]
    public ?int $nbPages;

    /**
     * @param ?string $titre
     * @param ?string $isbn
     * @param ?string $auteur
     * @param ?string $dateCreation
     * @param ?int $nbPages
    */

    public function __construct(?string $titre, ?string $isbn, ?string $auteur, ?string $dateCreation, ?int $nbPages=null)
    {
        $this->titre = $titre;
        $this->isbn = $isbn;
        $this->auteur = $auteur;
        $this->nbPages = $nbPages;
        $this->dateCreation = $dateCreation;
    }


}