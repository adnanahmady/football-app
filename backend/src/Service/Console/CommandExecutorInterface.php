<?php

namespace App\Service\Console;

use Symfony\Component\HttpKernel\KernelInterface;

interface CommandExecutorInterface
{
    public function __construct(KernelInterface $bootedKernel);

    public function execute(
        string $command,
        array $options = []
    ): void;
}
