<?php

namespace App\Tests\Traits;

use App\Service\Console\CommandExecutor;
use App\Service\Console\CommandExecutorInterface;
use Symfony\Component\HttpKernel\KernelInterface;

trait MigrateDatabaseTrait
{
    private function getCommandExecutor(): CommandExecutorInterface
    {
        return new CommandExecutor(
            bootedKernel: $this->getBootedKernel()
        );
    }

    abstract private function getBootedKernel(): KernelInterface;

    protected function setUpDatabaseMigration(): void
    {
        $this->getCommandExecutor()->execute(
            'doctrine:migrations:migrate',
        );
    }

    protected function tearDownDatabaseMigration(): void
    {
        $this->getCommandExecutor()->execute(
            'doctrine:migrations:rollup',
        );
    }
}
