<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Utilisateur
 *
 * @ORM\Table(name="utilisateur", uniqueConstraints={@ORM\UniqueConstraint(name="email_utilisateur", columns={"email_utilisateur"})})
 * @ORM\Entity
 */
class Utilisateur
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_utilisateur", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idUtilisateur;

    /**
     * @Assert\NotBlank(message="Nom doit etre non vide")
     * @Assert\Length(
     *  min =5,
     *  minMessage="Nom doit etre > 5 "
     * )
     * @var string
     *
     * @ORM\Column(name="nom_utilisateur", type="string", length=50, nullable=false)
     */
    private $nomUtilisateur;

    /**
     * @Assert\NotBlank(message="Date de Naissqnce doit etre non vide")
     * @Assert\LessThan("-12 years")
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_naissance_utilisateur", type="date", nullable=true)
     */
    private $dateNaissanceUtilisateur;

    /**
     * @var string|null
     *
     * @ORM\Column(name="photo_utilisateur", type="string", length=200, nullable=true)
     */
    private $photoUtilisateur;

    /**
     * @Assert\NotBlank(message="Type doit etre non vide")
     * @var int
     *
     * @ORM\Column(name="type_utilisateur", type="integer", nullable=false)
     */
    private $typeUtilisateur;

    /**
     * @var int
     *
     * @ORM\Column(name="solde_utilisateur", type="integer", nullable=false)
     */
    private $soldeUtilisateur = '500';

    /**
     * @Assert\NotBlank(message="Email doit etre non vide")
     * @var string|null
     *
     * @ORM\Column(name="email_utilisateur", type="string", length=50, nullable=true)
     */
    private $emailUtilisateur;

    /**
     * @var int|null
     * @ORM\Column(type="integer")
     */
    private $numero_utilisateur;


    public function getIdUtilisateur(): ?int
    {
        return $this->idUtilisateur;
    }

    public function getNomUtilisateur(): ?string
    {
        return $this->nomUtilisateur;
    }

    public function setNomUtilisateur(string $nomUtilisateur): self
    {
        $this->nomUtilisateur = $nomUtilisateur;

        return $this;
    }

    public function getDateNaissanceUtilisateur(): ?\DateTimeInterface
    {
        if($this->dateNaissanceUtilisateur!=null)
        {
            $this->dateNaissanceUtilisateur->setTimezone(new \DateTimeZone('+0800'));
        }
        return $this->dateNaissanceUtilisateur;
    }

    public function setDateNaissanceUtilisateur(?\DateTimeInterface $dateNaissanceUtilisateur): self
    {
        if($dateNaissanceUtilisateur!=null)
        {
            $dateNaissanceUtilisateur->setTimezone(new \DateTimeZone('+0800'));
        }
        $this->dateNaissanceUtilisateur = $dateNaissanceUtilisateur;

        return $this;
    }

    public function getPhotoUtilisateur(): ?string
    {
        return $this->photoUtilisateur;
    }

    public function setPhotoUtilisateur(?string $photoUtilisateur): self
    {
        $this->photoUtilisateur = $photoUtilisateur;

        return $this;
    }

    public function getTypeUtilisateur(): ?int
    {
        return $this->typeUtilisateur;
    }

    public function setTypeUtilisateur(int $typeUtilisateur): self
    {
        $this->typeUtilisateur = $typeUtilisateur;

        return $this;
    }

    public function setId(int $id): self
    {
        $this->idUtilisateur=$id;

        return $this;
    }

    public function getSoldeUtilisateur(): ?int
    {
        return $this->soldeUtilisateur;
    }

    public function setSoldeUtilisateur(int $soldeUtilisateur): self
    {
        $this->soldeUtilisateur = $soldeUtilisateur;

        return $this;
    }

    public function getEmailUtilisateur(): ?string
    {
        return $this->emailUtilisateur;
    }

    public function setEmailUtilisateur(?string $emailUtilisateur): self
    {
        $this->emailUtilisateur = $emailUtilisateur;

        return $this;
    }

    public function __toString() {
        return strval($this->idUtilisateur);
    }

    public function getNumeroUtilisateur(): ?int
    {
        return $this->numero_utilisateur;
    }

    public function setNumeroUtilisateur(int $numero_utilisateur): self
    {
        $this->numero_utilisateur = $numero_utilisateur;

        return $this;
    }




}
