<?php

namespace App\Factory;

use App\Entity\Team;
use App\Entity\User;
use App\Service\Factory\AbstractFactory;

class TeamPlayerContractFactory extends AbstractFactory
{
    protected function initiate(array $fields): array
    {
        return [
            'team' => $fields['team'] ??
                $this->factory(Team::class)->create(),
            'player' => $fields['player'] ??
                $this->factory(User::class)->create(),
            'amount' => $this->faker->randomNumber(),
            'startAt' => new \DateTimeImmutable(
                $this->dateTimeBetween('-10 years', '-3 months')
            ),
            'endAt' => new \DateTimeImmutable(
                $this->dateTimeBetween('now', '+10 years')
            ),
        ];
    }

    private function dateTimeBetween(string $start, string $end): string
    {
        return $this->faker
            ->dateTimeBetween(startDate: $start, endDate: $end)
            ->format('Y-m-d H:i:s');
    }
}
