<?php

namespace App\Entity;

use App\Repository\LoginRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Login
 *
 * @ORM\Table(name="login", uniqueConstraints={@ORM\UniqueConstraint(name="email_login", columns={"email_login"})})
 * @ORM\Entity(repositoryClass="App\Repository\LoginRepository")
 */
class Login implements UserInterface
{
    /**
     * @Assert\NotBlank(message="Email doit etre non vide")
     * @var string
     *
     * @ORM\Column(name="email_login", type="string", length=50, nullable=false)
     */
    private $emailLogin;

    /**
     * @Assert\NotBlank(message="Mot de passe doit etre non vide")
     * @var string
     *
     * @ORM\Column(name="mdp_login", type="string", length=150, nullable=false)
     */
    private $mdpLogin;

    /**
     * @var bool
     *
     * @ORM\Column(name="blocked_login", type="boolean", nullable=false)
     */
    private $blockedLogin = '0';

    /**
     *
     * @var string|null
     *
     * @ORM\Column(name="blocked_message", type="string", length=150, nullable=true)
     */
    private $blockedMessage;

    /**
     *
     * @var \DateTime|null
     *
     * @ORM\Column(name="blocked_duree", type="date", nullable=true)
     */
    private $blockedDuree;

    /**
     * @Assert\NotBlank(message="L'utilisateur doit etre non vide")
     * @var \Utilisateur
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_login", referencedColumnName="id_utilisateur")
     * })
     */
    private $idLogin;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $activation_token;

    public function getEmailLogin(): ?string
    {
        return $this->emailLogin;
    }

    public function setEmailLogin(string $emailLogin): self
    {
        $this->emailLogin = $emailLogin;

        return $this;
    }

    public function getMdpLogin(): ?string
    {
        return $this->mdpLogin;
    }

    public function setMdpLogin(string $mdpLogin): self
    {
        $this->mdpLogin = $mdpLogin;

        return $this;
    }

    public function getBlockedLogin(): ?bool
    {
        return $this->blockedLogin;
    }

    public function setBlockedLogin(bool $blockedLogin): self
    {
        $this->blockedLogin = $blockedLogin;

        return $this;
    }

    public function getBlockedMessage(): ?string
    {
        return $this->blockedMessage;
    }

    public function setBlockedMessage(?string $blockedMessage): self
    {
        $this->blockedMessage = $blockedMessage;

        return $this;
    }

    public function getBlockedDuree(): ?\DateTimeInterface
    {
        return $this->blockedDuree;
    }

    public function setBlockedDuree(?\DateTimeInterface $blockedDuree): self
    {
        $this->blockedDuree = $blockedDuree;

        return $this;
    }

    public function getIdLogin(): ?Utilisateur
    {
        return $this->idLogin;
    }

    public function setIdLogin(?Utilisateur $idLogin): self
    {
        $this->idLogin = $idLogin;

        return $this;
    }


    public function getRoles(): array
    {
        switch ($this->getIdLogin()->getTypeUtilisateur()) {
            case 1:
                return ['ROLE_USER'];
            case 2:
                return ['ROLE_ECRIVAIN'];
            case 3:
                return ['ROLE_MAISONEDITION'];
            case 4:
                return ['ROLE_ADMIN'];
        }
        return ['ROLE_USER'];
    }

    public function getPassword(): ?string
    {
        return $this->mdpLogin;
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername(): int
    {
        return (int)$this->getIdLogin()->getIdUtilisateur();
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function __toString()
    {

        return strval($this->idLogin);


    }

    public function getActivationToken(): ?string
    {
        return $this->activation_token;
    }

    public function setActivationToken(?string $activation_token): self
    {
        $this->activation_token = $activation_token;

        return $this;
    }

}
