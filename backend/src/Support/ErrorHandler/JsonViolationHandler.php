<?php

namespace App\Support\ErrorHandler;

use App\Exception\ValidationException;
use App\Support\ErrorHandler\Bag\ErrorMessageBag;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class JsonViolationHandler
{
    public function __construct(
        protected ConstraintViolationListInterface $violationList,
    ) {
    }

    public function handle(): void
    {
        if (!$this->violationList->count()) {
            return;
        }
        $this->sendResponse();
    }

    private function sendResponse(): void
    {
        throw new ValidationException($this->getErrorBag());
    }

    private function getErrorBag(): ErrorMessageBag
    {
        $messages = new ErrorMessageBag(message: 'Validation failed');

        foreach ($this->violationList as $error) {
            $messages->addError(
                $error->getPropertyPath(),
                $error->getInvalidValue(),
                $error->getMessage()
            );
        }

        return $messages;
    }
}
