<?php

namespace App\Entity;

use App\Repository\CrenauxRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CrenauxRepository::class)]
class Crenaux
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $heure_debut = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $heure_fin = null;

    #[ORM\Column(length: 1, nullable: true)]
    private ?string $salle = null;

    #[ORM\ManyToOne(inversedBy: 'crenaux')]
    private ?Matiere $Matiere = null;

    /**
     * @var Collection<int, Participer>
     */
    #[ORM\OneToMany(targetEntity: Participer::class, mappedBy: 'Crenaux')]
    private Collection $participers;

    public function __construct()
    {
        $this->participers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getHeureDebut(): ?\DateTimeInterface
    {
        return $this->heure_debut;
    }

    public function setHeureDebut(\DateTimeInterface $heure_debut): static
    {
        $this->heure_debut = $heure_debut;

        return $this;
    }

    public function getHeureFin(): ?\DateTimeInterface
    {
        return $this->heure_fin;
    }

    public function setHeureFin(\DateTimeInterface $heure_fin): static
    {
        $this->heure_fin = $heure_fin;

        return $this;
    }

    public function getSalle(): ?string
    {
        return $this->salle;
    }

    public function setSalle(?string $salle): static
    {
        $this->salle = $salle;

        return $this;
    }

    public function getMatiere(): ?Matiere
    {
        return $this->Matiere;
    }

    public function setMatiere(?Matiere $Matiere): static
    {
        $this->Matiere = $Matiere;

        return $this;
    }

    /**
     * @return Collection<int, Participer>
     */
    public function getParticipers(): Collection
    {
        return $this->participers;
    }

    public function addParticiper(Participer $participer): static
    {
        if (!$this->participers->contains($participer)) {
            $this->participers->add($participer);
            $participer->setCrenaux($this);
        }

        return $this;
    }

    public function removeParticiper(Participer $participer): static
    {
        if ($this->participers->removeElement($participer)) {
            // set the owning side to null (unless already changed)
            if ($participer->getCrenaux() === $this) {
                $participer->setCrenaux(null);
            }
        }

        return $this;
    }
}
