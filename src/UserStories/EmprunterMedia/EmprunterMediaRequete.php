<?php

namespace App\UserStories\EmprunterMedia;

use App\Entites\Adherent;
use App\Entites\Media;
use Symfony\Component\Validator\Constraints as Assert;

class EmprunterMediaRequete
{
    #[Assert\NotBlank(
        message: "Le numéro d'adhérent doit être renseigné !"
    )]
    public ?string $adherent;
    #[Assert\NotNull(
        message: "L'id du média doit être renseigné !"
    )]
    #[Assert\GreaterThan(
        value: 0, message: "L'id du média doit être supérieur à 0 !"
    )]
    public ?int $media;

    /**
     * @param ?string $adherent
     * @param ?int $media
     */
    public function __construct(?string $adherent, ?int $media=null)
    {
        $this->adherent = $adherent;
        $this->media = $media;
    }

}