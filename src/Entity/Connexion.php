<?php

namespace App\Entity;

use App\Repository\ConnexionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConnexionRepository::class)]
class Connexion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $DateConnexion = null;

    #[ORM\ManyToOne(inversedBy: 'connexions')]
    #[ORM\JoinColumn(name: 'id_connexion', referencedColumnName:'id',nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }




    public function getDateConnexion(): ?\DateTimeImmutable
{
    return $this->DateConnexion;
}

public function setDateConnexion(\DateTimeImmutable $DateConnexion): self
{
    $this->DateConnexion = $DateConnexion;

    return $this;
}

public function getUser(): ?User
{
    return $this->user;
}

public function setUser(?User $user): self
{
    $this->user = $user;

    return $this;
}



  
}
