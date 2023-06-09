<?php

namespace App\Support\RequestValidator;

trait PropertiesSetterTrait
{
    abstract protected function data(): array;

    abstract protected function object(): object;

    protected function populate(): void
    {
        foreach ($this->data() as $property => $value) {
            $this->assignProperty($property, $value);
        }
    }

    private function assignProperty(string $property, $value): void
    {
        $preparedProperty = $this->prepareProperty($property);
        $hasSetter = $this->hasSetter(
            $setter = $this->getSetterName($preparedProperty)
        );

        if ($hasSetter) {
            $this->object()->{$setter}($value);

            return;
        }

        if (property_exists($this->object(), $preparedProperty)) {
            $this->object()->{$preparedProperty} = $value;
        }
    }

    private function prepareProperty(string $property): string
    {
        $preparedProperty = preg_split('/[-_]/', $property);
        $items = [];
        foreach ($preparedProperty as $i => $item) {
            $items[] = $i < 1 ? $item : ucfirst($item);
        }

        return join('', $items);
    }

    private function getSetterName(string $preparedProperty): string
    {
        return 'set' . ucfirst($preparedProperty);
    }

    private function hasSetter(string $setter): bool
    {
        return method_exists($this->object(), $setter);
    }
}
