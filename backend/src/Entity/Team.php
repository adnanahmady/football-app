<?php

namespace App\Entity;

use App\EntityGroup\TeamGroup;
use App\Repository\TeamRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
class Team
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([TeamGroup::CREATE])]
    private ?int $id = null;

    #[ORM\Column(length: 60)]
    #[Groups([TeamGroup::CREATE])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups([TeamGroup::CREATE])]
    private ?int $moneyBalance = null;

    #[ORM\ManyToOne(inversedBy: 'teams')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([TeamGroup::CREATE])]
    private ?Country $country = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getMoneyBalance(): ?int
    {
        return $this->moneyBalance;
    }

    public function setMoneyBalance(int $moneyBalance): self
    {
        $this->moneyBalance = $moneyBalance;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }
}
