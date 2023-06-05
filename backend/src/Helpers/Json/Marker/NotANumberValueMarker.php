<?php

namespace App\Helpers\Json\Marker;

use App\Helpers\Json\ValueType\NotANumberValueType;
use App\Helpers\Json\ValueType\ValueTypeInterface;

class NotANumberValueMarker extends AbstractMarker
{
    protected function pattern(): string
    {
        return '/nan/i';
    }

    protected function valueType(): ValueTypeInterface
    {
        return new NotANumberValueType();
    }
}
