<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * CategorieLivre
 *
 * @ORM\Table(name="categorie_livre")
 * @ORM\Entity(repositoryClass="App\Repository\CategorieLivrecRepository")
 */
class CategorieLivre
{
    /**
     * @var int
     *a
     * @ORM\Column(name="id_categorie_livre", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCategorieLivre;

    /**
     * @Assert\NotBlank(message=" libelle doit etre non vide")
     * @Assert\Length(
     *      min = 3,
     *      minMessage=" Entrer un libelle au mini de 3 caracteres"
     *
     *     )
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    public function getIdCategorieLivre(): ?int
    {
        return $this->idCategorieLivre;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }
    public function __toString() {
        return $this->libelle;
    }


}
