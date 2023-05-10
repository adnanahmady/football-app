<?php

namespace App\Service\Factory;

final class Counter implements CounterInterface
{
    public function __construct(
        private int $count = 0
    ) {
    }

    public function setCount(int $count): CounterInterface
    {
        $this->count = $count;

        return $this;
    }

    public function count(callable $fn): array|object
    {
        $item = [];
        $counter = 0;

        do {
            $item = $fn();
            $items[] = $item;
        } while (++$counter < $this->count);

        return $this->count ? $items : $item;
    }
}
