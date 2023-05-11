<?php

namespace App\Request;

use App\Service\RequestValidator\AbstractRequestValidator;
use Symfony\Component\Validator\Constraints as Assert;

class CreateCountryRequest extends AbstractRequestValidator
{
    #[Assert\Length(min: 3)]
    #[Assert\NotBlank]
    protected mixed $name;

    public function getName(): string
    {
        return $this->name;
    }
}
