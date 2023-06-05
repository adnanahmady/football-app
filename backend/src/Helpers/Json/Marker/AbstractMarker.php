<?php

namespace App\Helpers\Json\Marker;

use App\Helpers\Json\ValueType\ValueTypeInterface;

abstract class AbstractMarker
{
    public function __construct(readonly protected string $jsonString)
    {
    }

    public function __toString(): string
    {
        return preg_replace(
            $this->pattern(),
            $this->valueType(),
            $this->jsonString
        );
    }

    abstract protected function pattern(): string;

    abstract protected function valueType(): ValueTypeInterface;
}
