<?php

namespace App\Exception;

use App\Service\ErrorHandler\Bag\ErrorMessageBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class FormValidationException extends HttpException implements PublishedMessageException
{
    public function __construct(
        readonly private ErrorMessageBagInterface $messageBag,
        int $statusCode = Response::HTTP_FOUND,
        \Throwable $previous = null,
        array $headers = [],
        int $code = 0
    ) {
        $message = json_encode($messageBag->toArray());
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }

    public function getArrayMessage(): array
    {
        return $this->messageBag->getErrors();
    }
}
