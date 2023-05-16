<?php

namespace App\Service\Constraints;

use App\Service\DataTypes\IntegerType;
use App\Service\DataTypes\TypeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

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

        if (null === $value || '' === $value || !preg_match('/^-?\d+$/', $value)) {
            return;
        }

        if ($value < 1) {
            throw new UnexpectedValueException($value, EntityExists::class);
        }

        if ($this->isNotValid($constraint, new IntegerType($value))) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ entity }}', $constraint->entity)
                ->addViolation();
        }
    }

    private function isNotValid(
        EntityExists $constraint,
        TypeInterface $entityId
    ): bool {
        return
            !class_exists($constraint->entity) ||
            !$this->findById($constraint, $entityId);
    }

    private function findById(
        EntityExists $constraint,
        TypeInterface $entityId
    ): ?object {
        return $this
            ->getRepository($constraint)
            ->findOneBy(['id' => $entityId->getValue()]);
    }

    private function getRepository(
        EntityExists $constraint
    ): EntityRepository {
        return $this->entityManager
            ->getRepository($constraint->entity);
    }
}
