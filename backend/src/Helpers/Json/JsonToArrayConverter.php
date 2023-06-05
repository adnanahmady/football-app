<?php

namespace App\Helpers\Json;

use App\Helpers\Json\Marker\InfiniteValueMarker;
use App\Helpers\Json\Marker\NotANumberValueMarker;

class JsonToArrayConverter implements ConverterInterface
{
    private bool $hasAutoCorrection = false;

    public function __construct(private string $jsonString)
    {
    }

    public function autoCorrect(): self
    {
        $this->hasAutoCorrection = true;
        $this->jsonString = new NotANumberValueMarker($this->jsonString);
        $this->jsonString = new InfiniteValueMarker($this->jsonString);

        return $this;
    }

    public function convert(): array|false
    {
        $converter = new JsonToArrayDecoder($this->jsonString);

        if (!$result = $converter->decode()) {
            return false;
        }

        if (!$this->hasAutoCorrection) {
            return $result;
        }

        return (new ArrayMarkedValueResolver($result))->resolve();
    }
}
