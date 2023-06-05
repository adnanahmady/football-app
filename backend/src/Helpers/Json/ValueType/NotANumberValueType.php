<?php

namespace App\Helpers\Json\ValueType;

class NotANumberValueType extends AbstractType
{
    public function getValue(): int|float
    {
        return NAN;
    }
}
