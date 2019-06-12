<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentaireRepository")
 */
class Commentaire
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="commentaires")
     * @ORM\JoinColumn(nullable=false)
     */
    private $auteur;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Commentaire", inversedBy="commentaires")
     */
    private $commentaireParent;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Commentaire", mappedBy="commentaireParent")
     */
    private $commentaires;

    public function __toString()
    {
       return $this->content;
    }

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuteur(): ?User
    {
        return $this->auteur;
    }

    public function setAuteur(?User $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCommentaireParent(): ?self
    {
        return $this->commentaireParent;
    }

    public function setCommentaireParent(?self $commentaireParent): self
    {
        $this->commentaireParent = $commentaireParent;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(self $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setCommentaireParent($this);
        }

        return $this;
    }

    public function removeCommentaire(self $commentaire): self
    {
        if ($this->commentaires->contains($commentaire)) {
            $this->commentaires->removeElement($commentaire);
            // set the owning side to null (unless already changed)
            if ($commentaire->getCommentaireParent() === $this) {
                $commentaire->setCommentaireParent(null);
            }
        }

        return $this;
    }
}
