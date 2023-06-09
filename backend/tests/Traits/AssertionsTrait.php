<?php

namespace App\Tests\Traits;

trait AssertionsTrait
{
    protected function assertArrayHasKeys(array $keys, array $base): void
    {
        foreach ($keys as $key) {
            $this->assertArrayHasKey($key, $base);
        }
    }

    protected function assertIsNan($value, string $message = ''): void
    {
        $this->assertIsNumeric($value, $message);
        $this->assertIsNotInt($value, $message);
        $this->assertIsFloat($value, $message);
    }

    protected function assertIsInfinite($value, string $message = ''): void
    {
        $this->assertGreaterThan(PHP_INT_MAX, $value, $message);
        $this->assertIsNumeric($value, $message);
        $this->assertIsNotInt($value, $message);
        $this->assertIsFloat($value, $message);
    }
}
