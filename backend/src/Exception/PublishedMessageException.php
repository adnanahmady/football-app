<?php

namespace App\Exception;

interface PublishedMessageException
{
    public function getArrayMessage(): array;
}
