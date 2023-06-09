<?php

namespace App\DTO;

use App\Entity\Team;
use App\Entity\User;

class UserDTO
{
    private int $id;
    private string $fullName;
    private string $email;
    private array $userRoles;
    private bool $isVerified;
    private ?Team $currentPlayerTeam;

    public function __construct(User $user)
    {
        $this->setId($user->getId());
        $this->setFullName($user->getFullName());
        $this->setEmail($user->getEmail());
        $this->setUserRoles($user->getRoles());
        $this->setIsVerified($user->isVerified());
        $this->setCurrentPlayerTeam($user->getCurrentPlayingTeam());
    }

    public function getId(): int
    {
        return $this->id;
    }

    private function setId(int $id): void
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

    public function getEmail(): string
    {
        return $this->email;
    }

    private function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getUserRoles(): array
    {
        return $this->userRoles;
    }

    private function setUserRoles(array $userRoles): void
    {
        $this->userRoles = $userRoles;
    }

    public function getIsVerified(): bool
    {
        return $this->isVerified;
    }

    private function setIsVerified(bool $isVerified): void
    {
        $this->isVerified = $isVerified;
    }

    public function getCurrentPlayerTeam(): ?Team
    {
        return $this->currentPlayerTeam;
    }

    private function setCurrentPlayerTeam(?Team $currentPlayerTeam): void
    {
        $this->currentPlayerTeam = $currentPlayerTeam;
    }
}
