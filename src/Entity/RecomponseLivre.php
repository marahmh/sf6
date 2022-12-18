<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * RecomponseLivre
 *
 * @ORM\Table(name="recomponse_livre", indexes={@ORM\Index(name="fk_type_recomponse", columns={"id_recomponse"}), @ORM\Index(name="fk_livre_recompnse", columns={"id_livre"})})
 * @ORM\Entity
 */
class RecomponseLivre
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="quantite", type="integer", nullable=false)
     * @Assert\NotNull
     * @Assert\NotEqualTo(
     *     value = 0
     *     )
     */
    private $quantite = '0';

    /**
     * @var \Livre
     *
     * @ORM\ManyToOne(targetEntity="Livre")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_livre", referencedColumnName="id_livre")
     * })
     */
    private $idLivre;

    /**
     * @var \Recomponse
     *
     * @ORM\ManyToOne(targetEntity="Recomponse")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_recomponse", referencedColumnName="id_recomponse")
     * })
     */
    private $idRecomponse;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getIdLivre(): ?Livre
    {
        return $this->idLivre;
    }

    public function setIdLivre(?Livre $idLivre): self
    {
        $this->idLivre = $idLivre;

        return $this;
    }

    public function getIdRecomponse(): ?Recomponse
    {
        return $this->idRecomponse;
    }

    public function setIdRecomponse(?Recomponse $idRecomponse): self
    {
        $this->idRecomponse = $idRecomponse;

        return $this;
    }




}
