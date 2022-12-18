<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\RecomponseRepository;


/**
 * Recomponse
 *
 * @ORM\Table(name="recomponse")
 *@ORM\Entity
 */
class Recomponse
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_recomponse", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idRecomponse;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_recomponse", type="string", length=30, nullable=false)
     * @Assert\NotNull
     */
    private $nomRecomponse;

    /**
     * @var string
     *
     * @ORM\Column(name="description_recomponse", type="string", length=70, nullable=false)
     * @Assert\NotNull
     */
    private $descriptionRecomponse;

    /**
     * @var string
     *
     * @ORM\Column(name="photo_recomponse", type="string", length=200, nullable=false)
     * @Assert\NotNull
     */
    private $photoRecomponse;

    /**
     * @var int
     *
     * @ORM\Column(name="prix_recomponse", type="integer", nullable=false)
     * @Assert\NotNull
     * @Assert\NotEqualTo(
     *     value = 0
     * )
     */
    private $prixRecomponse;

    public function getIdRecomponse(): ?int
    {
        return $this->idRecomponse;
    }

    public function getNomRecomponse(): ?string
    {
        return $this->nomRecomponse;
    }

    public function setNomRecomponse(string $nomRecomponse): self
    {
        $this->nomRecomponse = $nomRecomponse;

        return $this;
    }

    public function getDescriptionRecomponse(): ?string
    {
        return $this->descriptionRecomponse;
    }

    public function setDescriptionRecomponse(string $descriptionRecomponse): self
    {
        $this->descriptionRecomponse = $descriptionRecomponse;

        return $this;
    }

    public function getPhotoRecomponse()
    {
        return $this->photoRecomponse;
    }

    public function setPhotoRecomponse($photoRecomponse)
    {
        $this->photoRecomponse = $photoRecomponse;

        return $this;
    }

    public function getPrixRecomponse(): ?int
    {
        return $this->prixRecomponse;
    }

    public function setPrixRecomponse(int $prixRecomponse): self
    {
        $this->prixRecomponse = $prixRecomponse;

        return $this;
    }
    public function __toString()
    {
        return (string) $this->idRecomponse;
    }


}
