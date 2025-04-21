<?php

namespace App\Entity;

use App\Repository\ParticiperRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticiperRepository::class)]
class Participer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $signature = null;

    #[ORM\ManyToOne(inversedBy: 'participers')]
    private ?User $User = null;

    #[ORM\ManyToOne(inversedBy: 'participers')]
    private ?Crenaux $Crenaux = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSignature(): ?string
    {
        return $this->signature;
    }

    public function setSignature(?string $signature): static
    {
        $this->signature = $signature;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): static
    {
        $this->User = $User;

        return $this;
    }

    public function getCrenaux(): ?Crenaux
    {
        return $this->Crenaux;
    }

    public function setCrenaux(?Crenaux $Crenaux): static
    {
        $this->Crenaux = $Crenaux;

        return $this;
    }
}
