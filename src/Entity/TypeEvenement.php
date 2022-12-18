<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * TypeEvenement
 *
 * @ORM\Table(name="type_evenement")
 * @ORM\Entity
 */
class TypeEvenement
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_type_evenement", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idTypeEvenement;

    /**
     * @var string
     *@Assert\NotBlank(message="Le libelle est obligatoire ")
     * @ORM\Column(name="libelle_type_evenement", type="string", length=20, nullable=false)
     */
    private $libelleTypeEvenement;

    public function getIdTypeEvenement(): ?int
    {
        return $this->idTypeEvenement;
    }

    public function getLibelleTypeEvenement(): ?string
    {
        return $this->libelleTypeEvenement;
    }

    public function setLibelleTypeEvenement(string $libelleTypeEvenement): self
    {
        $this->libelleTypeEvenement = $libelleTypeEvenement;

        return $this;
    }
    public function __toString() {
        return $this->libelleTypeEvenement;
    }

}
