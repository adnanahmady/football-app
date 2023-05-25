<?php

namespace App\Constraints;

use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
class UniqueEntity extends Constraint
{
    public string $message = 'The specified "{{ field }}" already exist!';

    #[HasNamedArguments]
    public function __construct(
        public string $entity,
        public null|string $field = null,
        array $groups = null,
        mixed $payload = null
    ) {
        parent::__construct([], $groups, $payload);
    }
}
