<?php

namespace App\Exception;

class InvalidMethodNameException extends \Exception
{
    public static function throwIf(bool $condition, string $message): void
    {
        if ($condition) {
            throw new static($message);
        }
    }
}
