<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vangrg\ProfanityBundle\Storage\ProfanitiesStorageDefault;
use Vangrg\ProfanityBundle\VangrgProfanityBundle;


/**
 * CommentaireBlog
 *
 * @ORM\Table(name="commentaire_blog", indexes={@ORM\Index(name="fk_commentaireB_blog", columns={"id_blog"}), @ORM\Index(name="fk_commentaireB_utilisateur", columns={"id_utilisateur"})})
 * @ORM\Entity
 */
class CommentaireBlog
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_commentaire", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCommentaire;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_commentaire", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $dateCommentaire = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="contenu_commentaire", type="string", length=700, nullable=false)
     */
    private $contenuCommentaire;

    /**
     * @var \Blog
     *
     * @ORM\ManyToOne(targetEntity="Blog")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_blog", referencedColumnName="id_blog")
     * })
     */
    private $idBlog;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_utilisateur", referencedColumnName="id_utilisateur")
     * })
     */
    private $idUtilisateur;



    public function getIdCommentaire(): ?int
    {
        return $this->idCommentaire;
    }

    public function getDateCommentaire(): ?\DateTimeInterface
    {
        return $this->dateCommentaire;
    }

    public function setDateCommentaire(\DateTimeInterface $dateCommentaire): self
    {
        $this->dateCommentaire = $dateCommentaire;

        return $this;
    }

    public function getContenuCommentaire(): ?string
    {
        return $this->contenuCommentaire;
    }

    public function setContenuCommentaire(string $contenuCommentaire): self
    {
        $this->contenuCommentaire = $contenuCommentaire;

        return $this;
    }

    public function getIdBlog(): ?Blog
    {
        return $this->idBlog;
    }

    public function setIdBlog(?Blog $idBlog): self
    {
        $this->idBlog = $idBlog;

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
