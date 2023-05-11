<?php

namespace App\EventListener;

use App\Exception\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ValidationExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof ValidationException) {
            return;
        }
        $event->setResponse(new JsonResponse(
            $exception->getArrayMessage(),
            $exception->getStatusCode(),
        ));
    }
}
