<?php

namespace App\Request;

use App\Entity\Team;
use App\Service\Constraints\EntityExists;
use App\Service\RequestValidator\AbstractRequestValidator;
use Symfony\Component\Validator\Constraints as Assert;

class CreatePlayerRequest extends AbstractRequestValidator
{
    #[Assert\NotBlank]
    protected null|string $name;

    #[Assert\NotBlank]
    protected null|string $surname;

    #[EntityExists(entity: Team::class)]
    #[Assert\NotBlank]
    #[Assert\Type('numeric')]
    protected mixed $teamId;

    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    #[Assert\GreaterThan(100)]
    protected mixed $amount;

    #[Assert\NotBlank]
    #[Assert\LessThan('today')]
    protected null|\DateTimeImmutable $startAt;

    #[Assert\NotBlank]
    #[Assert\GreaterThan('today')]
    protected null|\DateTimeImmutable $endAt;

    public function setStartAt(null|string|array $startAt): void
    {
        $this->startAt = $this->getAsImmutable(
            is_array($startAt) ?
                $this->dateToString($startAt) :
                $startAt
        );
    }

    public function setEndAt(null|string|array $endAt): void
    {
        $this->endAt = $this->getAsImmutable(
            is_array($endAt) ?
                $this->dateToString($endAt) :
                $endAt
        );
    }

    private function dateToString(array $datetime): string
    {
        $datetime = array_map(
            fn ($s) => strlen($s) < 2 ? "0$s" : $s,
            array_merge(...array_values($datetime))
        );

        return sprintf(
            '%s-%s-%s %s:%s:00',
            $datetime['year'],
            $datetime['month'],
            $datetime['day'],
            $datetime['hour'],
            $datetime['minute'],
        );
    }

    private function getAsImmutable(
        null|string $datetime
    ): null|\DateTimeImmutable {
        return $datetime ? new \DateTimeImmutable($datetime) : null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function getTeamId(): ?int
    {
        return $this->teamId;
    }

    public function getStartAt(): ?\DateTimeImmutable
    {
        return $this->startAt;
    }

    public function getEndAt(): ?\DateTimeImmutable
    {
        return $this->endAt;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }
}
