<?php

namespace App\Support\Factory;

interface CounterInterface
{
    public function __construct(int $count = 0);

    public function setCount(int $count): CounterInterface;

    public function count(callable $fn): array|object;
}
