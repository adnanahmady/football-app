<?php

namespace App\Tests\Traits;

use App\Support\Factory\FactoryInterface;
use App\Support\Factory\Initiator;
use Doctrine\ORM\EntityManagerInterface;

trait HasFactoryTrait
{
    protected function factory(string $entity): FactoryInterface
    {
        return (new Initiator($this->getManager()))->initiate($entity);
    }

    private function getManager(): EntityManagerInterface
    {
        /** @var EntityManagerInterface $item */
        $item = $this->getContainer()->get(
            EntityManagerInterface::class
        );

        return $item;
    }
}
