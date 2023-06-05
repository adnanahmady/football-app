<?php

namespace App\Helpers\Json;

use App\Helpers\Json\ValueType\ValueTypeInterface;

class ArrayMarkedValueResolver
{
    public function __construct(private array $data)
    {
    }

    public function resolve(): array
    {
        array_walk_recursive($this->data, function ($value, $key): void {
            if ($this->isAValueType($value)) {
                $this->data[$key] = $this->resolveValueType(new $value());
            }
        });

        return $this->data;
    }

    private function resolveValueType(ValueTypeInterface $valueType): mixed
    {
        return $valueType->getValue();
    }

    private function isAValueType($value): bool
    {
        return is_subclass_of($value, ValueTypeInterface::class);
    }
}
