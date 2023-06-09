<?php

namespace App\ValueObject\User;

class FullNameValue
{
    public function __construct(
        readonly private string $name,
        readonly private string $surname,
    ) {
    }

    public function __toString(): string
    {
        return $this->getFullName();
    }

    public function getFullName(): string
    {
        return sprintf('%s %s', $this->name, $this->surname);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }
}
