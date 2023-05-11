<?php

namespace App\Factory;

use App\Service\Factory\AbstractFactory;

class UserFactory extends AbstractFactory
{
    protected function initiate(array $fields): array
    {
        return [
            'name' => $this->faker->name(),
            'surname' => $this->faker->lastName(),
        ];
    }
}
