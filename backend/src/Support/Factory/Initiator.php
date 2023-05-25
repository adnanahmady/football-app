<?php

namespace App\Support\Factory;

use Doctrine\ORM\EntityManagerInterface;

class Initiator
{
    public function __construct(
        readonly private EntityManagerInterface $entityManager
    ) {
    }

    public function initiate(string $entity): FactoryInterface
    {
        $Factory = sprintf(
            'App\\Factory\\%sFactory',
            $this->getEntityName($entity)
        );

        return new $Factory(
            entity: $entity,
            entityManager: $this->entityManager
        );
    }

    private function getEntityName(string $entity): string
    {
        return preg_replace(
            pattern: '/^.*\\\\/',
            replacement: '',
            subject: $entity
        );
    }
}
