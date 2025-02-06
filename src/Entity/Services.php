<?php

namespace App\Entity;

use App\Repository\ServicesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ServicesRepository::class)]
class Services
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['appointment:read'])] 
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['appointment:read'])] 
    private ?string $name = null;

    #[ORM\Column(length: 200, nullable: true)]
    #[Groups(['appointment:read'])] 
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['appointment:read'])] 
    private ?int $duration_minutes = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDurationMinutes(): ?int
    {
        return $this->duration_minutes;
    }

    public function setDurationMinutes(int $duration_minutes): static
    {
        $this->duration_minutes = $duration_minutes;

        return $this;
    }
}
