<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ProgrammingLanguagesAreNotOccupied extends Constraint
{
    public $message = 'One of your programming language is already occupied';
}