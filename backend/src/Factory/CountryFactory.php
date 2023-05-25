<?php

namespace App\Factory;

use App\Support\Factory\AbstractFactory;

class CountryFactory extends AbstractFactory
{
    protected function initiate(array $fields): array
    {
        return [
            'name' => $this->faker->country(),
        ];
    }
}
