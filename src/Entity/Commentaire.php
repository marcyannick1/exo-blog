<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $Commentaire = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $Date_de_publication = null;

    #[ORM\Column(length: 255)]
    private ?string $Etat = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $Auteur = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Article $Article = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommentaire(): ?string
    {
        return $this->Commentaire;
    }

    public function setCommentaire(string $Commentaire): static
    {
        $this->Commentaire = $Commentaire;

        return $this;
    }

    public function getDateDePublication(): ?\DateTimeInterface
    {
        return $this->Date_de_publication;
    }

    public function setDateDePublication(\DateTimeInterface $Date_de_publication): static
    {
        $this->Date_de_publication = $Date_de_publication;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->Etat;
    }

    public function setEtat(string $Etat): static
    {
        $this->Etat = $Etat;

        return $this;
    }

    public function getAuteur(): ?Utilisateur
    {
        return $this->Auteur;
    }

    public function setAuteur(?Utilisateur $Auteur): static
    {
        $this->Auteur = $Auteur;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->Article;
    }

    public function setArticle(?Article $Article): static
    {
        $this->Article = $Article;

        return $this;
    }
}
