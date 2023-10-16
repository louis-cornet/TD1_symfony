<?php

namespace App\Entity;

use App\Repository\PublicationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PublicationRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Publication
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(min: 4, minMessage: 'Il faut au moins 4 caractères!')]

    #[Assert\Length(
        groups: ["publication:write:normal"],
        min: 4,
        max: 50,
        maxMessage: 'Si vous voulez avoir acces a plus de characters, passez a premium.'
    )]
    #[Assert\Length(
        groups: ["publication:write:premium"],
        min: 4,
        max: 200,
        maxMessage: 'La limite de characters a été dépassé'
    )]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $message = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $datePublication = null;

    #[ORM\ManyToOne(inversedBy: 'publications', fetch: 'EAGER')]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?Utilisateur $auteur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getDatePublication(): ?\DateTimeInterface
    {
        return $this->datePublication;
    }

    public function setDatePublication(\DateTimeInterface $datePublication): static
    {
        $this->datePublication = $datePublication;
        return $this;
    }

    #[ORM\PrePersist]
    public function prePersistDatePublication () : void
    {
        $this->datePublication = new  \DateTime();
    }

    public function getAuteur(): ?Utilisateur
    {
        return $this->auteur;
    }

    public function setAuteur(?Utilisateur $auteur): static
    {
        $this->auteur = $auteur;

        return $this;
    }
}
