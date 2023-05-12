<?php

namespace App\DTO\ContractPlayer;

use App\Entity\User;

class PlayerDTO
{
    private int $id;
    private string $fullName;

    public function __construct(User $player)
    {
        $this->setId($player->getId());
        $this->setFullName(sprintf(
            '%s %s',
            $player->getName(),
            $player->getSurname(),
        ));
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): void
    {
        $this->fullName = $fullName;
    }
}
