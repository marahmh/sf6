<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;
use DateInterval;
use DateTimeZone;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Livre
 *
 * @ORM\Table(name="livre", indexes={@ORM\Index(name="fk_livre_categorie", columns={"id_categorie_livre"}), @ORM\Index(name="fk_livre_utilisateur", columns={"id_ecrivain_livre"})})
 * @ORM\Entity(repositoryClass="App\Repository\LivreRepository")
 */
class Livre
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_livre", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idLivre;

    /**
     * @Assert\NotBlank(message=" titre doit etre non vide")
     * @Assert\Length(
     *      min = 5,
     *      minMessage=" Entrer un titre au mini de 5 caracteres"
     *
     *     )
     * @ORM\Column(type="string", length=255)
     */
    private $titreLivre;

    /**
     * @Assert\NotBlank(message="description  doit etre non vide")
     * @Assert\Length(
     *      min = 7,
     *      max = 100,
     *      minMessage = "doit etre >=7 ",
     *      maxMessage = "doit etre <=100" )
     * @ORM\Column(type="string", length=1000)
     */
    private $descriptionLivre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_publication_livre", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $datePublicationLivre;

    /**
     * @var string
     *
     * @ORM\Column(name="photo_livre", type="string", length=200, nullable=false)
     */
    private $photoLivre;

    /**
     * @Assert\NotBlank(message="Contenu  doit etre non vide")

     * @ORM\Column(type="string", length=1000)
     */
    private $contenuLivre;

    /**
     *
     * @var int
     * @ORM\Column(name="prix_livre", type="integer", nullable=false)
     */
    private $prixLivre = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="evalution_livre", type="integer", nullable=false)
     */
    private $evalutionLivre = '0';

    /**
     * @var \CategorieLivre
     *
     * @ORM\ManyToOne(targetEntity="CategorieLivre")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_categorie_livre", referencedColumnName="id_categorie_livre")
     * })
     */
    private $idCategorieLivre;

    /**
     * @var Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_ecrivain_livre", referencedColumnName="id_utilisateur")
     * })
     */
    private $idEcrivainLivre;

    public function getIdLivre(): ?int
    {
        return $this->idLivre;
    }
    public function setIdLivre(?Livre $idLivre): ?int
    {
        $this->idLivre=$idLivre;
        return $this;
    }

    public function getTitreLivre(): ?string
    {
        return $this->titreLivre;
    }

    public function setTitreLivre(string $titreLivre): self
    {
        $this->titreLivre = $titreLivre;

        return $this;
    }

    public function getDescriptionLivre(): ?string
    {
        return $this->descriptionLivre;
    }

    public function setDescriptionLivre(string $descriptionLivre): self
    {
        $this->descriptionLivre = $descriptionLivre;

        return $this;
    }

    public function getDatePublicationLivre(): ?\DateTimeInterface
    {
        return $this->datePublicationLivre;
    }

    public function setDatePublicationLivre(\DateTimeInterface $datePublicationLivre): self
    {
        $this->datePublicationLivre = $datePublicationLivre;

        return $this;
    }

    public function getPhotoLivre(): ?string
    {
        return $this->photoLivre;
    }

    public function setPhotoLivre(string $photoLivre): self
    {
        $this->photoLivre = $photoLivre;

        return $this;
    }

    public function getContenuLivre(): ?string
    {
        return $this->contenuLivre;
    }

    public function setContenuLivre(string $contenuLivre): self
    {
        $this->contenuLivre = $contenuLivre;

        return $this;
    }

    public function getPrixLivre(): ?int
    {
        return $this->prixLivre;
    }

    public function setPrixLivre(int $prixLivre): self
    {
        $this->prixLivre = $prixLivre;

        return $this;
    }

    public function getEvalutionLivre(): ?int
    {
        return $this->evalutionLivre;
    }

    public function setEvalutionLivre(int $evalutionLivre): self
    {
        $this->evalutionLivre = $evalutionLivre;

        return $this;
    }

    public function getIdCategorieLivre(): ?CategorieLivre
    {
        return $this->idCategorieLivre;
    }

    public function setIdCategorieLivre(?CategorieLivre $idCategorieLivre): self
    {
        $this->idCategorieLivre = $idCategorieLivre;

        return $this;
    }

    public function getIdEcrivainLivre(): ?Utilisateur
    {
        return $this->idEcrivainLivre;
    }

    public function setIdEcrivainLivre(?Utilisateur $idEcrivainLivre): self
    {
        $this->idEcrivainLivre = $idEcrivainLivre;

        return $this;
    }

    public function __toString() {
        return $this->titreLivre;
    }

}
