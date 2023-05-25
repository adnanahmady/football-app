<?php

namespace App\Support\Factory;

use Doctrine\ORM\EntityManagerInterface;

interface FactoryInterface
{
    public function __construct(
        string $entity,
        EntityManagerInterface $entityManager
    );

    public function setCount(int $count): FactoryInterface;

    public function create(
        array $fields = [],
        mixed ...$rest
    ): array|object;
}
