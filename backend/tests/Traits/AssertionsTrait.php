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
}
