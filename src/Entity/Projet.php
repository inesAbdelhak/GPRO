<?php

namespace App\Entity;

use App\Repository\ProjetRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ProjetRepository::class)]
#[UniqueEntity(
    fields: ['Acronyme'],
    message: 'Cet acronyme existe déjà. Veuillez en choisir un autre.'
)]
class Projet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(message: "L'acronyme ne peut pas être vide")]
    private ?string $Acronyme = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le titre ne peut pas être vide")]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z0-9\s\-\_\.]+$/',
        message: 'Le titre ne peut contenir que des lettres, chiffres, espaces et les caractères - _ .'
    )]
    private ?string $Titre = null;

    #[ORM\ManyToOne(inversedBy: 'projets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAcronyme(): ?string
    {
        return $this->Acronyme;
    }

    public function setAcronyme(string $Acronyme): static
    {
        $this->Acronyme = $Acronyme;
        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->Titre;
    }

    public function setTitre(string $Titre): static
    {
        $this->Titre = $Titre;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }
}