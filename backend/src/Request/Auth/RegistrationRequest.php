<?php

namespace App\Request\Auth;

use App\Constraints\UniqueEntity;
use App\Entity\User;
use App\Support\RequestValidator\AbstractRequestValidator;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationRequest extends AbstractRequestValidator
{
    #[Assert\NotBlank]
    #[Assert\Type(type: ['string'])]
    private mixed $name = null;

    #[Assert\NotBlank]
    #[Assert\Type(type: ['string'])]
    private mixed $surname = null;

    #[Assert\NotBlank]
    #[Assert\Email]
    #[UniqueEntity(User::class, 'email')]
    #[Assert\Type(type: ['string'])]
    private mixed $email = null;

    #[Assert\NotBlank]
    #[Assert\Type(type: ['string'])]
    #[Assert\Length(min: 8)]
    private mixed $plainPassword = null;

    public function getName(): mixed
    {
        return $this->name;
    }

    public function setName(mixed $name): void
    {
        $this->name = $name;
    }

    public function getSurname(): mixed
    {
        return $this->surname;
    }

    public function setSurname(mixed $surname): void
    {
        $this->surname = $surname;
    }

    public function getEmail(): mixed
    {
        return $this->email;
    }

    public function setEmail(mixed $email): void
    {
        $this->email = $email;
    }

    public function getPlainPassword(): mixed
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(mixed $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }
}
