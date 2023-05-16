<?php

namespace App\Service\DataTypes;

class IntegerType implements TypeInterface
{
    private int $number;

    public function __construct(int|float|string $number)
    {
        $this->number = (int) $number;
    }

    public function getValue(): int
    {
        return $this->number;
    }
}
