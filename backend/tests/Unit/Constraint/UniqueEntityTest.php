<?php

namespace App\Tests\Unit\Constraint;

use App\Constraints\UniqueEntity;
use App\Entity\User;
use App\Tests\Traits\MigrateDatabaseTrait;
use App\Tests\WebTestCase;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UniqueEntityTest extends WebTestCase
{
    use MigrateDatabaseTrait;

    /**
     * @test
     */
    public function when_entity_property_name_is_specified_the_class_property_name_should_be_ignored(): void
    {
        $instance = new class () {
            #[UniqueEntity(entity: User::class, field: 'email')]
            private mixed $someField;

            public function setSomeField(mixed $value): void
            {
                $this->someField = $value;
            }
        };
        $user = $this->createUser();
        $instance->setSomeField($user->getEmail());
        $errors = $this->validate($instance);

        $this->assertCount(1, $errors);
        $this->assertStringContainsString(
            sprintf('The specified "%s" already exist!', $user->getEmail()),
            $errors[0]->getMessage()
        );
    }

    /**
     * @test
     */
    public function it_should_not_throw_any_violation_on_null_value(): void
    {
        $instance = $this->getInstance();
        $errors = $this->validate($instance);

        $this->assertCount(0, $errors);
    }

    /**
     * @test
     */
    public function with_no_other_field_should_no_error_get_thrown(): void
    {
        $instance = $this->getInstance();
        $instance->setEmail('user@email.com');
        $errors = $this->validate($instance);

        $this->assertCount(0, $errors);
    }

    /**
     * @test
     */
    public function it_validates_property_based_on_specified_entity_and_property_name(): void
    {
        $user = $this->createUser();
        $instance = $this->getInstance();
        $instance->setEmail($user->getEmail());
        $errors = $this->validate($instance);

        $this->assertCount(1, $errors);
        $this->assertStringContainsString(
            sprintf('The specified "%s" already exist!', $user->getEmail()),
            $errors[0]->getMessage()
        );
    }

    private function getInstance(): object
    {
        return new class () {
            #[UniqueEntity(entity: User::class)]
            private mixed $email;

            public function setEmail(mixed $email): void
            {
                $this->email = $email;
            }
        };
    }

    private function createUser(): User
    {
        return $this->factory(User::class)->create();
    }

    private function validate(object $instance): ConstraintViolationListInterface
    {
        /** @var ValidatorInterface $validator */
        $validator = $this->getContainer()->get(ValidatorInterface::class);

        return $validator->validate($instance);
    }
}
