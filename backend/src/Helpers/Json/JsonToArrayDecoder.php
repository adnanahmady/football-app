<?php

namespace App\Helpers\Json;

class JsonToArrayDecoder
{
    public function __construct(readonly private string $jsonString)
    {
    }

    public function decode(): array|false
    {
        return json_decode($this->jsonString, associative: true);
    }
}
