<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vangrg\ProfanityBundle\Validator\Constraints as ProfanityAssert;

/**
 * Blog
 *
 * @ORM\Table(name="blog", indexes={@ORM\Index(name="fk_blog_utilisateur", columns={"id_utilisateur"})})
 * @ORM\Entity
 */
class Blog
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_blog", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idBlog;

    /**
     * @var string
     *
     * @ORM\Column(name="titre_blog", type="string", length=60, nullable=false)
     * @Assert\Length(
     *      min = 2,
     *      max = 10,
     *      minMessage = "titre doit avoir au minimum {{ limit }} characters long",
     *      maxMessage = "titre ne doit pas avoir {{ limit }} characters")
     * @Assert\NotNull
     * @ProfanityAssert\ProfanityCheck
     */
    private $titreBlog;

    /**
     * @var string
     *
     * @ORM\Column(name="sujet_blog", type="string", length=1000, nullable=false)
     * @Assert\Length(
     *      min = 2,
     *      max = 20,
     *      minMessage = "sujt doit avoir au minimum {{ limit }} characters long",
     *      maxMessage = "sujet ne doit pas avoir {{ limit }} characters")
     * @Assert\NotNull
     */
    private $sujetBlog;

    /**
     * @var string|null
     *
     * @ORM\Column(name="photo_blog", type="string", length=200, nullable=true)
     *
     */
    private $photoBlog;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_blog", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $dateBlog = 'CURRENT_TIMESTAMP';

    /**
     * @var int
     *
     * @ORM\Column(name="like_blog", type="integer", nullable=false)
     */
    private $likeBlog = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="dislike_blog", type="integer", nullable=false)
     */
    private $dislikeBlog = '0';

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_utilisateur", referencedColumnName="id_utilisateur")
     * })
     */
    private $idUtilisateur;



    public function getIdBlog(): ?int
    {
        return $this->idBlog;
    }

    public function getTitreBlog(): ?string
    {
        return $this->titreBlog;
    }

    public function setTitreBlog(string $titreBlog): self
    {
        $this->titreBlog = $titreBlog;

        return $this;
    }

    public function getSujetBlog(): ?string
    {
        return $this->sujetBlog;
    }

    public function setSujetBlog(string $sujetBlog): self
    {
        $this->sujetBlog = $sujetBlog;

        return $this;
    }

    public function getPhotoBlog(): ?string
    {
        return $this->photoBlog;
    }

    public function setPhotoBlog(?string $photoBlog): self
    {
        $this->photoBlog = $photoBlog;

        return $this;
    }

    public function getDateBlog(): ?\DateTimeInterface
    {
        return $this->dateBlog;
    }

    public function setDateBlog(\DateTimeInterface $dateBlog): self
    {
        $this->dateBlog = $dateBlog;

        return $this;
    }

    public function getLikeBlog(): ?int
    {
        return $this->likeBlog;
    }

    public function setLikeBlog(int $likeBlog): self
    {
        $this->likeBlog = $likeBlog;

        return $this;
    }

    public function getDislikeBlog(): ?int
    {
        return $this->dislikeBlog;
    }

    public function setDislikeBlog(int $dislikeBlog): self
    {
        $this->dislikeBlog = $dislikeBlog;

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

    /**
     * @ORM\OneToMany(targetEntity=CommentaireBlog::class, mappedBy="idBlog", orphanRemoval=true)
     *
     */
    private $idCommentaire;


}
