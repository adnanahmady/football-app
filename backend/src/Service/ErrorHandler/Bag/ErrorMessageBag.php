<?php

namespace App\Service\ErrorHandler\Bag;

class ErrorMessageBag implements ErrorMessageBagInterface
{
    private array $errors = [];

    public function __construct(private string $message)
    {
    }

    public function addError(
        string $property,
        mixed $value,
        string $message
    ): void {
        $this->errors[] = compact('property', 'value', 'message');
    }

    public function toArray(): array
    {
        return [
            'message' => $this->message,
            'errors' => $this->errors,
        ];
    }
}
