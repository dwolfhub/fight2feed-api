<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PasswordResetCodeExists extends Constraint
{
    /**
     * @var string
     */
    public $resetTokenMessage = 'This password reset code does not exist.';

    /**
     * @var string
     */
    public $emailMessage = 'This combination of reset code and email do not match.';

    /**
     * @return array|string
     */
    public function getTargets()
    {
        return Constraint::CLASS_CONSTRAINT;
    }
}
