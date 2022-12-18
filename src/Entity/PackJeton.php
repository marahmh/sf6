<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PackJeton
 *
 * @ORM\Table(name="pack_jeton")
 * @ORM\Entity
 */
class PackJeton
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_pack", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idPack;

    /**
     * @var string
     *
     * @ORM\Column(name="description_pack", type="string", length=80, nullable=false)
     *  @Assert\NotNull
     */
    private $descriptionPack;

    /**
     * @var int
     *
     * @ORM\Column(name="quantie_pack", type="integer", nullable=false)
     *  @Assert\NotNull
     * @Assert\NotEqualTo(
     *     value = 0
     * )
     */
    private $quantiePack;

    /**
     * @var float
     *
     * @ORM\Column(name="prix_pack", type="float", precision=10, scale=0, nullable=false)
     *  @Assert\NotNull
     *  @Assert\NotEqualTo(
     *     value = 0
     * )
     */
    private $prixPack;

    public function getIdPack(): ?int
    {
        return $this->idPack;
    }

    public function getDescriptionPack(): ?string
    {
        return $this->descriptionPack;
    }

    public function setDescriptionPack(string $descriptionPack): self
    {
        $this->descriptionPack = $descriptionPack;

        return $this;
    }

    public function getQuantiePack(): ?int
    {
        return $this->quantiePack;
    }

    public function setQuantiePack(int $quantiePack): self
    {
        $this->quantiePack = $quantiePack;

        return $this;
    }

    public function getPrixPack(): ?float
    {
        return $this->prixPack;
    }

    public function setPrixPack(float $prixPack): self
    {
        $this->prixPack = $prixPack;

        return $this;
    }


}
