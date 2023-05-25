<?php

namespace App\Factory;

use App\Support\Factory\AbstractFactory;

class UserFactory extends AbstractFactory
{
    protected function initiate(array $fields): array
    {
        return [
            'name' => $this->faker->name(),
            'surname' => $this->faker->lastName(),
            'email' => $this->faker->email(),
            'password' => join('', [
                '$2y$13$jRxcnHaSNaHpTwGgJZazoeU',
                'MVmzdx7H7msR6Or9RarNmvRQVEJkUm',
            ]), // password
            'roles' => $fields['roles'] ?? [],
        ];
    }
}
