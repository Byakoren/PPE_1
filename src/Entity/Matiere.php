<?php

namespace App\Entity;

use App\Repository\MatiereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MatiereRepository::class)]
class Matiere
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $type = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'Matiere')]
    private Collection $users;

    /**
     * @var Collection<int, Crenaux>
     */
    #[ORM\OneToMany(targetEntity: Crenaux::class, mappedBy: 'Matiere')]
    private Collection $crenaux;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->crenaux = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setMatiere($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getMatiere() === $this) {
                $user->setMatiere(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Crenaux>
     */
    public function getCrenaux(): Collection
    {
        return $this->crenaux;
    }

    public function addCrenaux(Crenaux $crenaux): static
    {
        if (!$this->crenaux->contains($crenaux)) {
            $this->crenaux->add($crenaux);
            $crenaux->setMatiere($this);
        }

        return $this;
    }

    public function removeCrenaux(Crenaux $crenaux): static
    {
        if ($this->crenaux->removeElement($crenaux)) {
            // set the owning side to null (unless already changed)
            if ($crenaux->getMatiere() === $this) {
                $crenaux->setMatiere(null);
            }
        }

        return $this;
    }
}
