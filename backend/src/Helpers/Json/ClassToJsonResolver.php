<?php

namespace App\Helpers\Json;

use App\Helpers\Json\ValueType\ValueTypeInterface;

class ClassToJsonResolver
{
    public function __construct(readonly private ValueTypeInterface $type)
    {
    }

    public function __toString(): string
    {
        return sprintf('"%s"', $this->resolveNamespace($this->type::class));
    }

    private function resolveNamespace(string $class): string
    {
        return str_replace('\\', '\\\\\\\\', $class);
    }
}
