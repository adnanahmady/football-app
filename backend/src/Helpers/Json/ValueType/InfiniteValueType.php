<?php

namespace App\Helpers\Json\ValueType;

class InfiniteValueType extends AbstractType
{
    public function getValue(): int|float
    {
        return INF;
    }
}
