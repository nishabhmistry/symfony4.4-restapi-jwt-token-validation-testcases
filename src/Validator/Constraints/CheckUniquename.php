<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CheckUniquename extends Constraint
{
    public $message = 'Name should be unique.';
}
