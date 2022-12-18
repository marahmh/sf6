<?php

namespace App\Entity;
use App\Repository\RegisterEntityRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
class profileEntity implements UserInterface
{

    private $id;
    /**
     * @Assert\NotBlank(message="ce champs est obligatoire")
     */
    private $nom_utilisateur;

    /**
     * @return string
     */
    public function getConfirmeMotDePasse()
    {
        return $this->confirme_mot_de_passe;
    }

    /**
     * @param string $confirme_mot_de_passe
     */
    public function setConfirmeMotDePasse(string $confirme_mot_de_passe): void
    {
        $this->confirme_mot_de_passe = $confirme_mot_de_passe;
    }

    private $date_naissance_utilisateur;
    /**
     * @Assert\NotBlank(message="ce champs est obligatoire")
     */
    private $numero_utilisateur;
    private $photo_utilisateur;
    /**
     * @Assert\Email()
     * @Assert\NotBlank(message="ce champs est obligatoire")
     */
    private $email_login;

    /**
     * @return mixed
     */
    public function getNumeroUtilisateur()
    {
        return $this->numero_utilisateur;
    }

    /**
     * @param mixed $numero_utilisateur
     */
    public function setNumeroUtilisateur($numero_utilisateur): void
    {
        $this->numero_utilisateur = $numero_utilisateur;
    }

    /**
     * @return string
     */
    public function getAncMdpLogin()
    {
        return $this->anc_mdp_login;
    }

    /**
     * @param string $anc_mdp_login
     */
    public function setAncMdpLogin(string $anc_mdp_login): void
    {
        $this->anc_mdp_login = $anc_mdp_login;
    }
    /**
     * @var string

     * @Assert\Length(min="6" , minMessage="votre mot de passe doit contient minimum 6 caracteres")
     */
    private $anc_mdp_login;
    /**
     * @var string

     * @Assert\Length(min="6" , minMessage="votre mot de passe doit contient minimum 6 caracteres")
     */
    private $mdp_login;
    /**
     * @var string
     * @Assert\EqualTo(propertyPath="mdp_login", message="vous n'aver pas taper le meme mot de passe")
     */
    public $confirme_mot_de_passe;
    /**
     * @return mixed
     */
    public function getEmailLogin()
    {
        return $this->email_login;
    }

    /**
     * @param mixed $email_login
     */
    public function setEmailLogin($email_login): void
    {
        $this->email_login = $email_login;
    }

    /**
     * @return mixed
     */
    public function getMdpLogin()
    {
        return $this->mdp_login;
    }

    /**
     * @param mixed $mdp_login
     */
    public function setMdpLogin($mdp_login): void
    {
        $this->mdp_login = $mdp_login;
    }

    /**
     * @return mixed
     */
    public function getPhotoUtilisateur()
    {
        return $this->photo_utilisateur;
    }

    /**
     * @param mixed $photo_utilisateur
     */
    public function setPhotoUtilisateur(string $photo_utilisateur): self
    {
        $this->photo_utilisateur = $photo_utilisateur;

        return $this;
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomUtilisateur(): ?string
    {
        return $this->nom_utilisateur;
    }

    public function setNomUtilisateur(string $nom_utilisateur): self
    {
        $this->nom_utilisateur = $nom_utilisateur;

        return $this;
    }

    public function getDateNaissanceUtilisateur(): ?\DateTimeInterface
    {
        return $this->date_naissance_utilisateur;
    }

    public function setDateNaissanceUtilisateur(?\DateTimeInterface $dateNaissanceUtilisateur): self
    {
        $this->date_naissance_utilisateur = $dateNaissanceUtilisateur;

        return $this;
    }

    public function getRoles()
    {
        // TODO: Implement getRoles() method.
    }

    public function getPassword()
    {
        // TODO: Implement getPassword() method.
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function getUsername()
    {
        // TODO: Implement getUsername() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}