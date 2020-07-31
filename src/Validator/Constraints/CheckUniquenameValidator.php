<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityRepository;
use App\Repository\FootballLeagueRepository;

class CheckUniquenameValidator extends ConstraintValidator
{
    private $footballLeagueRepository;

    public function __construct(FootballLeagueRepository $footballLeagueRepository)
    {
        $this->footballLeagueRepository = $footballLeagueRepository;
    }

    public function validate($value, Constraint $constraint)
    {

        $result = $this->footballLeagueRepository->checkExistName($this->context->getRoot());
        
        if (!$constraint instanceof CheckUniquename) {
            throw new UnexpectedTypeException($constraint, CheckUniquename::class);
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
