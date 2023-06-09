<?php

namespace App\Request;

use App\Entity\Country;
use App\Constraints\EntityExists;
use App\Support\RequestValidator\AbstractRequestValidator;
use Symfony\Component\Validator\Constraints as Assert;

class CreateTeamRequest extends AbstractRequestValidator
{
    #[Assert\Length(min: 4)]
    #[Assert\NotBlank]
    protected mixed $name;

    #[Assert\Regex('/^\d+$/')]
    #[Assert\NotBlank]
    protected mixed $moneyBalance;

    #[EntityExists(Country::class)]
    #[Assert\Type('numeric')]
    #[Assert\NotBlank]
    protected mixed $countryId;

    public function getName(): string
    {
        return $this->name;
    }

    public function getMoneyBalance(): string
    {
        return $this->moneyBalance;
    }

    public function getCountryId(): int
    {
        return $this->countryId;
    }
}
