<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AchatRecomponse
 *
 * @ORM\Table(name="achat_recomponse", indexes={@ORM\Index(name="fk_recompnse", columns={"id_recomponse"}), @ORM\Index(name="fk_achatR_utilisateur", columns={"id_utilisateur"})})
 * @ORM\Entity
 */
class AchatRecomponse
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
     * @var \Recomponse
     *
     * @ORM\ManyToOne(targetEntity="Recomponse")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_recomponse", referencedColumnName="id_recomponse")
     * })
     *
     */
    private $idRecomponse;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_utilisateur", referencedColumnName="id_utilisateur")
     * })
     */
    private $idUtilisateur;

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

    public function getIdRecomponse(): ?Recomponse
    {
        return $this->idRecomponse;
    }

    public function setIdRecomponse(?Recomponse $idRecomponse): self
    {
        $this->idRecomponse = $idRecomponse;

        return $this;
    }

    public function getIdUtilisateur(): ?Utilisateur
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(?Utilisateur $idUtilisateur): self
    {
        $this->idUtilisateur = $idUtilisateur;

        return $this;
    }


}
