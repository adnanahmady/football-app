<?php

namespace App\Request;

use App\Repository\CountryRepository;
use App\Service\RequestValidator\AbstractRequestValidator;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateTeamRequest extends AbstractRequestValidator
{
    #[Assert\Length(min: 4)]
    #[Assert\NotBlank]
    protected mixed $name;

    #[Assert\Type('integer')]
    #[Assert\NotBlank]
    protected mixed $moneyBalance;

    #[Assert\Type('integer')]
    #[Assert\NotBlank]
    protected mixed $countryId;

    public function __construct(
        ValidatorInterface $validator,
        RequestStack $requestStack,
        readonly private CountryRepository $countryRepository,
    ) {
        parent::__construct($validator, $requestStack);
    }

    /**
     * If country does not exist in system
     * then it should not get set as team
     * country.
     *
     * @param mixed $countryId country id
     */
    public function setCountryId(mixed $countryId): void
    {
        if (!$this->countryRepository->findOneById($countryId)) {
            return;
        }
        $this->countryId = $countryId;
    }

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
