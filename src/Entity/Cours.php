<?php

namespace App\Entity;

use App\Repository\CoursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoursRepository::class)]
class Cours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaire = null;

    /**
     * @var Collection<int, Participer>
     */
    #[ORM\OneToMany(targetEntity: Participer::class, mappedBy: 'cours')]
    private Collection $participer;

    #[ORM\ManyToOne(inversedBy: 'cours')]
    private ?Groupe $groupe = null;

    #[ORM\ManyToOne(inversedBy: 'cours')]
    private ?Matiere $matiere = null;

    #[ORM\ManyToOne(inversedBy: 'cours')]
    private ?Crenaux $crenaux = null;

    public function __construct()
    {
        $this->participer = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * @return Collection<int, Participer>
     */
    public function getParticiper(): Collection
    {
        return $this->participer;
    }

    public function addParticiper(Participer $participer): static
    {
        if (!$this->participer->contains($participer)) {
            $this->participer->add($participer);
            $participer->setCours($this);
        }

        return $this;
    }

    public function removeParticiper(Participer $participer): static
    {
        if ($this->participer->removeElement($participer)) {
            // set the owning side to null (unless already changed)
            if ($participer->getCours() === $this) {
                $participer->setCours(null);
            }
        }

        return $this;
    }

    public function getGroupe(): ?Groupe
    {
        return $this->groupe;
    }

    public function setGroupe(?Groupe $groupe): static
    {
        $this->groupe = $groupe;

        return $this;
    }

    public function getMatiere(): ?Matiere
    {
        return $this->matiere;
    }

    public function setMatiere(?Matiere $matiere): static
    {
        $this->matiere = $matiere;

        return $this;
    }

    public function getCrenaux(): ?Crenaux
    {
        return $this->crenaux;
    }

    public function setCrenaux(?Crenaux $crenaux): static
    {
        $this->crenaux = $crenaux;

        return $this;
    }

}
