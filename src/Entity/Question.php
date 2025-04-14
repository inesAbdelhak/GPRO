<?php
namespace App\Entity;
use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(length: 255)]
    private ?string $NomQuestion = null;
   
    #[ORM\Column]
    private ?bool $Obligatoire = null;
    #[ORM\OneToMany(targetEntity: Reponse::class, mappedBy: 'idQuestion')]
    private Collection $reponses;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Item $item = null;

    #[ORM\OneToMany(targetEntity: Propositions::class, mappedBy: 'question')]
    private Collection $propositions;

    #[ORM\ManyToOne(targetEntity: Propositions::class)]
    private ?Propositions $questionCondition = null;
    

    #[ORM\ManyToOne(inversedBy: 'questions')]
    private ?Type $type = null;





    public function __construct()
    {
        $this->reponses = new ArrayCollection();
        $this->propositions = new ArrayCollection();
    }

    public function getQuestionCondition(): ?Propositions
    {
        return $this->questionCondition;
    }

    public function setQuestionCondition(?Propositions $questionCondition): self
    {
    $this->questionCondition = $questionCondition;
    return $this;
    }


    public function getId(): ?int
    {
        return $this->id;
    }
    public function getNomQuestion(): ?string
    {
        return $this->NomQuestion;
    }
    public function setNomQuestion(string $NomQuestion): self
    {
        $this->NomQuestion = $NomQuestion;
        return $this;
    }
    public function isObligatoire(): ?bool
    {
        return $this->Obligatoire;
    }
    public function setObligatoire(bool $Obligatoire): self
    {
        $this->Obligatoire = $Obligatoire;
        return $this;
    }
   
 
    public function getReponses(): Collection
    {
        return $this->reponses;
    }
    public function addReponse(Reponse $reponse): self
    {
        if (!$this->reponses->contains($reponse)) {
            $this->reponses->add($reponse);
            $reponse->setIdQuestion($this);
        }
        return $this;
    }
    public function removeReponse(Reponse $reponse): self
    {
        if ($this->reponses->removeElement($reponse)) {
            // set the owning side to null (unless already changed)
            if ($reponse->getIdQuestion() === $this) {
                $reponse->setIdQuestion(null);
            }
        }
        return $this;
    }
    public function getItem(): ?Item
    {
        return $this->item;
    }
    public function setItem(?Item $item): self
    {
        $this->item = $item;
        return $this;
    }
    /**
     * @return Collection<int, Propositions>
     */
    public function getPropositions(): Collection
    {
        return $this->propositions;
    }
    public function addProposition(Propositions $proposition): self
    {
        if (!$this->propositions->contains($proposition)) {
            $this->propositions->add($proposition);
            $proposition->setQuestion($this);
        }
        return $this;
    }
    public function removeProposition(Propositions $proposition): self
    {
        if ($this->propositions->removeElement($proposition)) {
            // set the owning side to null (unless already changed)
            if ($proposition->getQuestion() === $this) {
                $proposition->setQuestion(null);
            }
        }
        return $this;
    }
    public function getType(): ?Type
    {
        return $this->type;
    }
    public function setType(?Type $type): static
    {
        $this->type = $type;
        return $this;
    }
 
}