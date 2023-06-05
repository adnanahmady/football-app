<?php

namespace App\Helpers\Json;

interface ConverterInterface
{
    public function __construct(string $jsonString);

    public function autoCorrect(): ConverterInterface;

    public function convert(): array|false;
}
