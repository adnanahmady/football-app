<?php

namespace App\Service\Constraints;

use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
class EntityExists extends Constraint
{
    public string $message = 'The specified "{{ entity }}" does not exist.';

    #[HasNamedArguments]
    public function __construct(
        public string $entity,
        array $groups = null,
        mixed $payload = null
    ) {
        parent::__construct([], $groups, $payload);
    }
}
