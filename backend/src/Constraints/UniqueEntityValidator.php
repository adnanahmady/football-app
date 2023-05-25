<?php

namespace App\Constraints;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class UniqueEntityValidator extends ConstraintValidator
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueEntity) {
            throw new UnexpectedTypeException($constraint, UniqueEntity::class);
        }

        if (null === $value) {
            return;
        }

        if ('' === $value || !is_string($value)) {
            throw new UnexpectedValueException($value, UniqueEntity::class);
        }

        if ($this->isNotUnique($constraint, $value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ field }}', $value)
                ->addViolation();
        }
    }

    private function isNotUnique(UniqueEntity $constraint, string $value): bool
    {
        return !class_exists($constraint->entity) ||
            (bool) $this->findField($constraint, $value);
    }

    private function findField(UniqueEntity $constraint, string $value): ?object
    {
        $field = $constraint->field ?? $this->context->getPropertyPath();

        return $this->getRepository($constraint)
            ->findOneBy([$field => $value]);
    }

    private function getRepository(UniqueEntity $constraint): EntityRepository
    {
        return $this->entityManager
            ->getRepository($constraint->entity);
    }
}
