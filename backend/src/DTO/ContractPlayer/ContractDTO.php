<?php

namespace App\DTO\ContractPlayer;

use App\Entity\TeamPlayerContract;

class ContractDTO
{
    private int $id;
    private PlayerDTO $player;
    private TeamDTO $team;
    private int $amount;
    private string $startAt;
    private string $endAt;

    public function __construct(TeamPlayerContract $contract)
    {
        $this->setId($contract->getId());
        $this->setTeam(new TeamDTO($contract->getTeam()));
        $this->setPlayer(new PlayerDTO($contract->getPlayer()));
        $this->setAmount($contract->getAmount());
        $this->setStartAt($contract->getStartAt());
        $this->setEndAt($contract->getEndAt());
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getPlayer(): PlayerDTO
    {
        return $this->player;
    }

    public function setPlayer(PlayerDTO $player): void
    {
        $this->player = $player;
    }

    public function getTeam(): TeamDTO
    {
        return $this->team;
    }

    public function setTeam(TeamDTO $team): void
    {
        $this->team = $team;
    }

    public function getStartAt(): string
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeImmutable $startAt): void
    {
        $this->startAt = $this->format($startAt);
    }

    public function getEndAt(): string
    {
        return $this->endAt;
    }

    public function setEndAt(\DateTimeImmutable $endAt): void
    {
        $this->endAt = $this->format($endAt);
    }

    private function format(\DateTimeImmutable $datetime): string
    {
        return $datetime->format('Y-m-d H:i:s');
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }
}
