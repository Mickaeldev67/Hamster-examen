<?php

namespace App\Entity;

use App\Repository\HamsterRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: HamsterRepository::class)]
class Hamster
{
    public const HUNGER = 0;
    public const AGE = 0;
    public const GENRE_M = "m";
    public const GENRE_F = "f";
    public const ACTIVE = true;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['hamster','user'])]
    private ?int $id = null;

    #[Groups(['user','hamster'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[Groups(['user','hamster'])]
    #[ORM\Column(nullable: true)]
    private ?int $hunger = null;

    #[Groups(['user','hamster'])]
    #[ORM\Column(nullable: true)]
    private ?int $age = null;

    #[Groups(['user','hamster'])]
    #[ORM\Column(length: 10, nullable: true)]
    private ?string $genre = null;

    #[ORM\ManyToOne(inversedBy: 'hamsters')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['hamster'])]
    private ?User $owner = null;

    #[Groups(['user','hamster'])]
    #[ORM\Column(nullable: true)]
    private ?bool $active = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getHunger(): ?int
    {
        return $this->hunger;
    }

    public function setHunger(?int $hunger): static
    {
        $this->hunger = $hunger;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(?string $genre): static
    {
        $this->genre = $genre;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): static
    {
        $this->active = $active;

        return $this;
    }
}
