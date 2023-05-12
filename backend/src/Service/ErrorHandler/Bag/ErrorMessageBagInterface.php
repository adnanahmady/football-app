<?php

namespace App\Service\ErrorHandler\Bag;

interface ErrorMessageBagInterface
{
    public function addError(
        string $property,
        mixed $value,
        string $message
    ): void;

    public function toArray(): array;
}