<?php

namespace App\Service\RequestValidator;

use App\Exception\InvalidMethodNameException;
use App\Service\ErrorHandler\ViolationHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractRequestValidator
{
    use PropertiesSetterTrait;

    protected null|object $targetClass = null;

    public function __construct(
        protected ValidatorInterface $validator,
        protected RequestStack $requestStack
    ) {
        $this->populate();
        $this->throwIfItsNotValid();
    }

    protected function data(): array
    {
        return $this->toArray();
    }

    protected function object(): object
    {
        return $this->getTargetClass();
    }

    /**
     * Client can retrieve the same target
     * class that the request validator is
     * looking up to.
     */
    public function getTargetClass(): object
    {
        if (null === $this->targetClass) {
            $this->targetClass = $this->target();
        }

        return $this->targetClass;
    }

    /**
     * A request validator can target an entity
     * instead of itself.
     */
    protected function target(): object
    {
        return $this;
    }

    /**
     * If data is invalid then throws validation exception.
     */
    protected function throwIfItsNotValid(): void
    {
        $validator = new ViolationHandler($this->validate());

        $validator->handle();
    }

    /**
     * Validate the passed data.
     */
    public function validate(): ConstraintViolationListInterface
    {
        return $this->validator->validate($this->getTargetClass());
    }

    public function toArray(): array
    {
        return $this->request()->toArray();
    }

    public function get(string $key, mixed $default): mixed
    {
        return $this->request()->get($key, $default);
    }

    public function getContent(): string
    {
        return $this->request()->getContent();
    }

    protected function request(): null|Request
    {
        return $this->requestStack->getCurrentRequest();
    }

    public function __call(string $name, array $arguments): mixed
    {
        if (method_exists($this->request(), $name)) {
            return $this->request()->{$name}(...$arguments);
        }
        $message = sprintf(
            'method "%s" does not exist in "%s" request class',
            $name,
            get_called_class()
        );

        throw new InvalidMethodNameException($message);
    }
}
