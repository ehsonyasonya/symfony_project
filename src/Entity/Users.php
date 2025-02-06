<?php

namespace App\Entity;

use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\UsersRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:read','employee:read','schedule:read', 'appointment:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    #[Groups(['user:read','employee:read','schedule:read', 'appointment:read'])]
    private ?string $first_name = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 30)]
    #[Groups(['user:read','employee:read','schedule:read', 'appointment:read'])]
    private ?string $email = null;

    #[ORM\Column(type: "json")]
    private array $role = [];

    #[ORM\Column(length: 50)]
    #[Groups(['user:read','employee:read','schedule:read', 'appointment:read'])]
    private ?string $last_name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getRole(): array
    {
        return $this->role;
    }

    public function getRoles(): array
    {
        return array_map(fn($role) => strtoupper($role), $this->role);
    }

    public function setRoles(array $roles): self
    {
        $this->role = array_map(fn($role) => strtolower($role), $roles);
    
        return $this;
    }

    public function setRole(array $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): static
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {

    }
}
