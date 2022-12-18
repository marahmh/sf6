<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contrat
 *
 * @ORM\Table(name="contrat", indexes={@ORM\Index(name="fk_contrat_utilisateur", columns={"id_ecrivain"}), @ORM\Index(name="fk_contrat_maison", columns={"id_maison_edition"})})
 * @ORM\Entity
 */
class Contrat
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_contrat", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idContrat;

    /**
     * @var int
     *
     * @ORM\Column(name="duree_contrat", type="integer", nullable=false)
     */
    private $dureeContrat;

    /**
     * @var string
     *
     * @ORM\Column(name="description_contrat", type="string", length=300, nullable=false)
     */
    private $descriptionContrat;

    /**
     * @var \MasionEdition
     *
     * @ORM\ManyToOne(targetEntity="MasionEdition")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_maison_edition", referencedColumnName="id_maison_edition")
     * })
     */
    private $idMaisonEdition;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_ecrivain", referencedColumnName="id_utilisateur")
     * })
     */
    private $idEcrivain;

    public function getIdContrat(): ?int
    {
        return $this->idContrat;
    }

    public function getDureeContrat(): ?int
    {
        return $this->dureeContrat;
    }

    public function setDureeContrat(int $dureeContrat): self
    {
        $this->dureeContrat = $dureeContrat;

        return $this;
    }

    public function getDescriptionContrat(): ?string
    {
        return $this->descriptionContrat;
    }

    public function setDescriptionContrat(string $descriptionContrat): self
    {
        $this->descriptionContrat = $descriptionContrat;

        return $this;
    }

    public function getIdMaisonEdition(): ?MasionEdition
    {
        return $this->idMaisonEdition;
    }

    public function setIdMaisonEdition(?MasionEdition $idMaisonEdition): self
    {
        $this->idMaisonEdition = $idMaisonEdition;

        return $this;
    }

    public function getIdEcrivain(): ?Utilisateur
    {
        return $this->idEcrivain;
    }

    public function setIdEcrivain(?Utilisateur $idEcrivain): self
    {
        $this->idEcrivain = $idEcrivain;

        return $this;
    }


}
