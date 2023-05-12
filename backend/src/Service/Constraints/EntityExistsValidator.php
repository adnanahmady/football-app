<?php

namespace App\Service\Constraints;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class EntityExistsValidator extends ConstraintValidator
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof EntityExists) {
            throw new UnexpectedTypeException($constraint, EntityExists::class);
        }

        if (null === $value || '' === $value || !is_int($value)) {
            return;
        }

        if ($this->isNotValid($constraint, $value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ entity }}', $constraint->entity)
                ->addViolation();
        }
    }

    private function findById(
        EntityExists $constraint,
        int|string $value
    ): ?object {
        return $this
            ->getRepository($constraint)
            ->findOneBy(['id' => $value]);
    }

    private function getRepository(
        EntityExists $constraint
    ): EntityRepository {
        return $this->entityManager
            ->getRepository($constraint->entity);
    }

    private function isNotValid(
        EntityExists $constraint,
        int $value
    ): bool {
        return
            !class_exists($constraint->entity) ||
            !$this->findById($constraint, $value)
        ;
    }
}
