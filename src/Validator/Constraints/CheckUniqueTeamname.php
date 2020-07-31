<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CheckUniqueTeamname extends Constraint
{
    public $message = 'Team name should be unique.';
}
