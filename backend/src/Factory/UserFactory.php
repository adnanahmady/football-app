<?php

namespace App\Factory;

use App\Support\Factory\AbstractFactory;
use App\ValueObject\User\FullNameValue;

class UserFactory extends AbstractFactory
{
    protected function initiate(array $fields): array
    {
        return [
            'fullName' => new FullNameValue(
                $this->faker->name(),
                $this->faker->lastName(),
            ),
            'email' => $this->faker->email(),
            'password' => join('', [
                '$2y$13$jRxcnHaSNaHpTwGgJZazoeU',
                'MVmzdx7H7msR6Or9RarNmvRQVEJkUm',
            ]), // password
            'roles' => $fields['roles'] ?? [],
        ];
    }
}
