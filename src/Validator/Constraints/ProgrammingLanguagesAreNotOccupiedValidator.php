<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ProgrammingLanguagesAreNotOccupiedValidator extends ConstraintValidator
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        $foundUsers = $this->userRepository->findUsersWithProgrammingLanguages($value);

        if(count($foundUsers) > 0) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}