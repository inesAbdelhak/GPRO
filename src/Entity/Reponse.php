<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReponseRepository::class)]
class Reponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $ValeurReponse = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $DateReponse = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DateModification = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DateSupression = null;

    #[ORM\OneToMany(targetEntity: AuditTrail::class, mappedBy: 'reponse')]
    private Collection $audit;

    #[ORM\ManyToOne(inversedBy: 'reponses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Question $idQuestion = null;

    #[ORM\ManyToOne(targetEntity: Propositions::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Propositions $proposition = null;

    public function __construct()
    {
        $this->audit = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValeurReponse(): ?string
    {
        return $this->ValeurReponse;
    }

    public function setValeurReponse(string $ValeurReponse): self
    {
        $this->ValeurReponse = $ValeurReponse;

        return $this;
    }

    public function getDateReponse(): ?\DateTimeInterface
    {
        return $this->DateReponse;
    }

    public function setDateReponse(\DateTimeInterface $DateReponse): self
    {
        $this->DateReponse = $DateReponse;

        return $this;
    }

    public function getDateModification(): ?\DateTimeInterface
    {
        return $this->DateModification;
    }

    public function setDateModification(\DateTimeInterface $DateModification): self
    {
        $this->DateModification = $DateModification;

        return $this;
    }

    public function getDateSupression(): ?\DateTimeInterface
    {
        return $this->DateSupression;
    }

    public function setDateSupression(\DateTimeInterface $DateSupression): self
    {
        $this->DateSupression = $DateSupression;

        return $this;
    }

    /**
     * @return Collection<int, AuditTrail>
     */
    public function getAudit(): Collection
    {
        return $this->audit;
    }

    public function addAudit(AuditTrail $audit): self
    {
        if (!$this->audit->contains($audit)) {
            $this->audit->add($audit);
            $audit->setReponse($this);
        }

        return $this;
    }

    public function removeAudit(AuditTrail $audit): self
    {
        if ($this->audit->removeElement($audit)) {
            // set the owning side to null (unless already changed)
            if ($audit->getReponse() === $this) {
                $audit->setReponse(null);
            }
        }

        return $this;
    }

    

    public function getIdQuestion(): ?Question
    {
        return $this->idQuestion;
    }

    public function setIdQuestion(?Question $idQuestion): self
    {
        $this->idQuestion = $idQuestion;

        return $this;
    }




    public function getProposition(): ?Propositions
    {
        return $this->proposition;
    }
    
    public function setProposition(?Propositions $proposition): self
    {
        $this->proposition = $proposition;
        return $this;
    }
}
