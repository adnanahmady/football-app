<?php

namespace App\Helpers\Json\ValueType;

use App\Helpers\Json\ClassToJsonResolver;

abstract class AbstractType implements ValueTypeInterface
{
    public function __toString(): string
    {
        return new ClassToJsonResolver($this);
    }
}
