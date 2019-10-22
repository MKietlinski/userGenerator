<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Pesel\Pesel;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidPeselValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if(!Pesel::isValid($value)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}