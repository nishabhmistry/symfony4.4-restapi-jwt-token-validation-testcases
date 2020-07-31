<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\FootballTeamRepository;

class CheckUniqueTeamnameValidator extends ConstraintValidator
{
    private $footballTeamRepository;

    public function __construct(FootballTeamRepository $footballTeamRepository)
    {
        $this->footballTeamRepository = $footballTeamRepository;
    }

    public function validate($value, Constraint $constraint)
    {

        $result = $this->footballTeamRepository->checkExistName($this->context->getRoot());
        
        if (!$constraint instanceof CheckUniqueTeamname) {
            throw new UnexpectedTypeException($constraint, CheckUniqueTeamname::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) take care of that
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($value, 'string');

            // separate multiple types using pipes
            // throw new UnexpectedValueException($value, 'string|int');
        }
        
        if(count($result) > 0){
            // the argument must be a string or an object implementing __toString()
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }

}
