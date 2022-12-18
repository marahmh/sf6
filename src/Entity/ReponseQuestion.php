<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ReponseQuestion
 *
 * @ORM\Table(name="reponse_question", indexes={@ORM\Index(name="fk_question_reponse", columns={"id_question"})})
 * @ORM\Entity
 */
class ReponseQuestion
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
     * @var string
     *
     * @ORM\Column(name="text", type="string", length=200, nullable=false)
     */
    private $text;

    /**
     * @var bool
     *
     * @ORM\Column(name="etat", type="boolean", nullable=false)
     */
    private $etat;

    /**
     * @var \QuestionQuiz
     *
     * @ORM\ManyToOne(targetEntity="QuestionQuiz")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_question", referencedColumnName="id")
     * })
     */
    private $idQuestion;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getIdQuestion(): ?QuestionQuiz
    {
        return $this->idQuestion;
    }

    public function setIdQuestion(?QuestionQuiz $idQuestion): self
    {
        $this->idQuestion = $idQuestion;

        return $this;
    }


}
