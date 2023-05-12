<?php

namespace App\DTO\ContractPlayer;

use App\Entity\Team;

class TeamDTO
{
    private int $id;
    private string $name;

    public function __construct(Team $team)
    {
        $this->setId($team->getId());
        $this->setName($team->getName());
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
