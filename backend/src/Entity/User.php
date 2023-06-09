<?php

namespace App\Entity;

use App\Constants\RoleConstant;
use App\Repository\UserRepository;
use App\ValueObject\User\FullNameValue;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $surname = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(nullable: true)]
    private ?array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(nullable: true)]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'player', targetEntity: TeamPlayerContract::class, orphanRemoval: true)]
    private Collection $teamPlayerContracts;

    #[ORM\Column(type: 'boolean')]
    private bool $isVerified = false;

    private ?FullNameValue $fullNameValue;

    public function __construct()
    {
        $this->teamPlayerContracts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = RoleConstant::ROLE_USER;

        return array_unique($roles);
    }

    public function setRoles(?array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getCurrentPlayingTeam(): null|Team
    {
        return $this->teamPlayerContracts->last() ?: null;
    }

    public function getFullName(): FullNameValue
    {
        return $this->fullNameValue;
    }

    public function setFullName(FullNameValue $value): self
    {
        $this->fullNameValue = $value;
        $this->name = $value->getName();
        $this->surname = $value->getSurname();

        return $this;
    }

    /**
     * @return Collection<int, TeamPlayerContract>
     */
    public function getTeamPlayerContracts(): Collection
    {
        return $this->teamPlayerContracts;
    }

    public function addTeamPlayerContract(TeamPlayerContract $teamPlayerContract): self
    {
        if (!$this->teamPlayerContracts->contains($teamPlayerContract)) {
            $this->teamPlayerContracts->add($teamPlayerContract);
            $teamPlayerContract->setPlayer($this);
        }

        return $this;
    }

    public function removeTeamPlayerContract(TeamPlayerContract $teamPlayerContract): self
    {
        if ($this->teamPlayerContracts->removeElement($teamPlayerContract)) {
            // set the owning side to null (unless already changed)
            if ($teamPlayerContract->getPlayer() === $this) {
                $teamPlayerContract->setPlayer(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getFullName();
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(?bool $isVerified): self
    {
        $this->isVerified = (bool) $isVerified;

        return $this;
    }
}
