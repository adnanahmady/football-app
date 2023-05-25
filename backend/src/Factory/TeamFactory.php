<?php

namespace App\Factory;

use App\Entity\Country;
use App\Support\Factory\AbstractFactory;

class TeamFactory extends AbstractFactory
{
    protected function initiate(array $fields): array
    {
        return [
            'name' => $this->faker->name(),
            'moneyBalance' => $this->faker->randomNumber(),
            'country' => $fields['country'] ??
                $this->factory(Country::class)->create(),
        ];
    }
}
