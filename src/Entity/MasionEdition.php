<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MasionEdition
 *
 * @ORM\Table(name="masion_edition", indexes={@ORM\Index(name="id_admin_maison_edition", columns={"id_admin_maison_edition"})})
 * @ORM\Entity
 */
class MasionEdition
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_maison_edition", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idMaisonEdition;

    /**
     * @var string|null
     *
     * @ORM\Column(name="adresse_maison_edition", type="string", length=80, nullable=true)
     */
    private $adresseMaisonEdition;

    /**
     * @var string
     *
     * @ORM\Column(name="photo_maison_edition", type="string", length=200, nullable=false)
     */
    private $photoMaisonEdition;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description_maison_edition", type="string", length=300, nullable=true)
     */
    private $descriptionMaisonEdition;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_maison_edition", type="string", length=30, nullable=false)
     */
    private $nomMaisonEdition;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_admin_maison_edition", referencedColumnName="id_utilisateur")
     * })
     */
    private $idAdminMaisonEdition;

    public function getIdMaisonEdition(): ?int
    {
        return $this->idMaisonEdition;
    }

    public function getAdresseMaisonEdition(): ?string
    {
        return $this->adresseMaisonEdition;
    }

    public function setAdresseMaisonEdition(?string $adresseMaisonEdition): self
    {
        $this->adresseMaisonEdition = $adresseMaisonEdition;

        return $this;
    }

    public function getPhotoMaisonEdition(): ?string
    {
        return $this->photoMaisonEdition;
    }

    public function setPhotoMaisonEdition(string $photoMaisonEdition): self
    {
        $this->photoMaisonEdition = $photoMaisonEdition;

        return $this;
    }

    public function getDescriptionMaisonEdition(): ?string
    {
        return $this->descriptionMaisonEdition;
    }

    public function setDescriptionMaisonEdition(?string $descriptionMaisonEdition): self
    {
        $this->descriptionMaisonEdition = $descriptionMaisonEdition;

        return $this;
    }

    public function getNomMaisonEdition(): ?string
    {
        return $this->nomMaisonEdition;
    }

    public function setNomMaisonEdition(string $nomMaisonEdition): self
    {
        $this->nomMaisonEdition = $nomMaisonEdition;

        return $this;
    }

    public function getIdAdminMaisonEdition(): ?Utilisateur
    {
        return $this->idAdminMaisonEdition;
    }

    public function setIdAdminMaisonEdition(?Utilisateur $idAdminMaisonEdition): self
    {
        $this->idAdminMaisonEdition = $idAdminMaisonEdition;

        return $this;
    }


}
