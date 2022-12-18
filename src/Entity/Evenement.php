<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Evenement
 *
 * @ORM\Table(name="evenement", indexes={@ORM\Index(name="fk_evenement_type", columns={"type_evenement"}), @ORM\Index(name="fk_evenement_utilisateur", columns={"id_createur"})})
 * @ORM\Entity(repositoryClass="App\Repository\EvenementRepository")
 */
class Evenement
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_evenement", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idEvenement;

    /**
     * @Assert\NotBlank(message="Le titre est obligatoire ")
     * @var string
     *
     * @ORM\Column(name="titre_evenement", type="string", length=50, nullable=false)
     */
    private $titreEvenement;

    /**

     * @var string|null
     * @Assert\NotBlank(message="L'adresse est obligatoire ")
     * @ORM\Column(name="adresse_evenement", type="string", length=100, nullable=true)
     */
    private $adresseEvenement;

    /**
     * @Assert\NotBlank(message="La description l'evenement est oblogatoire")
     * @var string
     *
     * @ORM\Column(name="description_evenement", type="string", length=100, nullable=false)
     */
    private $descriptionEvenement;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="date_creation_evenement", type="datetime", nullable=false)
     */
    private $dateCreationEvenement;

    /**
     * @Assert\NotBlank(message="La date de l'evenement est obligatoire")
     * @Assert\GreaterThan("today")
     * @var DateTime
     *
     * @ORM\Column(name="date_evenement", type="datetime", nullable=false)
     */
    private $dateEvenement;

    /**
     * @var string|null
     *
     * @ORM\Column(name="image", type="string", length=200, nullable=true)
     */
    private $image;

    /**
     * @var \TypeEvenement
     *
     * @ORM\ManyToOne(targetEntity="TypeEvenement")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type_evenement", referencedColumnName="id_type_evenement")
     * })
     */
    private $typeEvenement;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_createur", referencedColumnName="id_utilisateur")
     * })
     */
    private $idCreateur;

    private $nbParticipant;

    private $estParticipe;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $longitude;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $latitude;

    public function getIdEvenement(): ?int
    {
        return $this->idEvenement;
    }

    public function getTitreEvenement(): ?string
    {
        return $this->titreEvenement;
    }

    public function setTitreEvenement(string $titreEvenement): self
    {
        $this->titreEvenement = $titreEvenement;

        return $this;
    }

    public function getAdresseEvenement(): ?string
    {
        return $this->adresseEvenement;
    }

    public function setAdresseEvenement(?string $adresseEvenement): self
    {
        $this->adresseEvenement = $adresseEvenement;

        return $this;
    }

    public function getDescriptionEvenement(): ?string
    {
        return $this->descriptionEvenement;
    }

    public function setDescriptionEvenement(string $descriptionEvenement): self
    {
        $this->descriptionEvenement = $descriptionEvenement;

        return $this;
    }

    public function getDateCreationEvenement(): DateTime

    {


        return $this->dateCreationEvenement;
    }

    public function setDateCreationEvenement(\DateTimeInterface $dateCreationEvenement): self
    {
        $this->dateCreationEvenement = $dateCreationEvenement;

        return $this;
    }

    public function getDateEvenement(): ?\DateTimeInterface
    {
        return $this->dateEvenement;
    }

    public function setDateEvenement(\DateTimeInterface $dateEvenement): self
    {
        $this->dateEvenement = $dateEvenement;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getTypeEvenement(): ?TypeEvenement
    {
        return $this->typeEvenement;
    }

    public function setTypeEvenement(?TypeEvenement $typeEvenement): self
    {
        $this->typeEvenement = $typeEvenement;

        return $this;
    }

    public function getIdCreateur(): ?Utilisateur
    {
        return $this->idCreateur;
    }

    public function setIdCreateur(?Utilisateur $idCreateur): self
    {
        $this->idCreateur = $idCreateur;

        return $this;
    }
    public function __toString() {
        return $this->titreEvenement;
    }

    /**
     * @return mixed
     */
    public function getEstParticipe()
    {
        return $this->estParticipe;
    }

    /**
     * @param mixed $estParticipe
     */
    public function setEstParticipe($estParticipe): void
    {
        $this->estParticipe = $estParticipe;
    }

    /**
     * @return mixed
     */
    public function getNbParticipant()
    {
        return $this->nbParticipant;
    }

    /**
     * @param mixed $nbParticipant
     */
    public function setNbParticipant($nbParticipant): void
    {
        $this->nbParticipant = $nbParticipant;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

}
