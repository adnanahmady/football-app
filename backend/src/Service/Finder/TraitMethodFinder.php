<?php

namespace App\Service\Finder;

class TraitMethodFinder
{
    private readonly \ReflectionClass $ref;

    public function __construct(
        object|string $objectOrClass
    ) {
        $this->ref = new \ReflectionClass($objectOrClass);
    }

    public function execute(
        string $pattern,
        callable $handler
    ): void {
        array_map(
            $handler,
            $this->keepPatternedMethods(
                $this->findMethods($pattern)
            )
        );
    }

    private function findMethods(string $pattern): array
    {
        return array_map(
            fn ($trait) => $this->getMethodNames(
                $trait,
                $pattern
            ),
            $this->ref->getTraits()
        );
    }

    private function getMethodNames(
        \ReflectionClass $trait,
        string $pattern
    ): string|null {
        $method = current(
            array_filter(
                $trait->getMethods(),
                fn ($method) => str_contains(
                    $method->getName(),
                    $pattern
                )
            )
        );

        return $method ? $method->name : null;
    }

    private function keepPatternedMethods(
        array $methods
    ): array {
        return array_filter($methods, 'is_string');
    }
}
