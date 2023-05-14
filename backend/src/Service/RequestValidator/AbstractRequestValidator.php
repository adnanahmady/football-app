<?php

namespace App\Service\RequestValidator;

use App\Exception\InvalidMethodNameException;
use App\Service\ErrorHandler\FormViolationHandler;
use App\Service\ErrorHandler\JsonViolationHandler;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractRequestValidator
{
    use PropertiesSetterTrait;

    protected null|object $targetClass = null;

    public function __construct(
        protected ValidatorInterface $validator,
        protected RequestStack $requestStack,
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
        if ($this->expectsJson()) {
            $handler = new JsonViolationHandler($this->validate());
            $handler->handle();

            return;
        }
        $handler = new FormViolationHandler(
            $this->validate()
        );
        $handler->handle();
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
        if ($this->expectsJson()) {
            return $this->request()->toArray();
        }
        $data = [];

        foreach ($this->request()->request->getIterator() as $key => $item) {
            $data = $this->setKey($data, $key, $item);
        }

        return $data;
    }

    private function setKey(array $data, string $key, mixed $value): array
    {
        if (is_string($value)) {
            $data[$key] = $value;

            return $data;
        }

        foreach ($value as $key => $part) {
            $data[$key] = $part;
        }

        return $data;
    }

    public function expectsJson(): bool
    {
        return 'json' === $this->request()->getContentTypeFormat();
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

    public function headers(): HeaderBag
    {
        return $this->request()->headers;
    }

    public function getSession(): SessionInterface
    {
        return $this->request()->getSession();
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
