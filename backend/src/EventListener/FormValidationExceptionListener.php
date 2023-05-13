<?php

namespace App\EventListener;

use App\Exception\FormValidationException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class FormValidationExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof FormValidationException) {
            return;
        }
        $event->getRequest()->getSession()->set('errors', $exception->getArrayMessage());
        $event->getRequest()->setMethod('GET');
        $event->setResponse(new RedirectResponse(
            $event->getRequest()->headers->get('referer'),
            $exception->getStatusCode(),
        ));
    }
}
