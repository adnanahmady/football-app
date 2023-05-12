<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User
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

    #[ORM\OneToMany(mappedBy: 'player', targetEntity: TeamPlayerContract::class, orphanRemoval: true)]
    private Collection $teamPlayerContracts;

    public function __construct()
    {
        $this->teamPlayerContracts = new ArrayCollection();
    }

    public function getCurrentPlayingTeam(): false|Team
    {
        return $this->teamPlayerContracts->last();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(null|string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(null|string $surname): self
    {
        $this->surname = $surname;

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
        return $this->getName() . ' ' . $this->getSurname();
    }
}
