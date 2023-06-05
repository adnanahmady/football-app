<?php

namespace App\Helpers\Json\Marker;

use App\Helpers\Json\ValueType\InfiniteValueType;
use App\Helpers\Json\ValueType\ValueTypeInterface;

class InfiniteValueMarker extends AbstractMarker
{
    protected function pattern(): string
    {
        return '/inf/i';
    }

    protected function valueType(): ValueTypeInterface
    {
        return new InfiniteValueType();
    }
}
