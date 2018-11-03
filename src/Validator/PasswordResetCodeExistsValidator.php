<?php

namespace App\Validator;

use App\DTO\PasswordResetSubmit;
use App\Repository\PasswordResetCodeRepository;
use DateTime;
use RuntimeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class PasswordResetCodeExistsValidator
 * @package App\Validator
 */
class PasswordResetCodeExistsValidator extends ConstraintValidator
{
    /**
     * @var PasswordResetCodeRepository
     */
    private $repository;

    /**
     * PasswordResetCodeExistsValidator constructor.
     *
     * @param PasswordResetCodeRepository $repository
     */
    public function __construct(PasswordResetCodeRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param PasswordResetSubmit $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        /** @var PasswordResetCodeExists $constraint */
        if (!$value instanceof PasswordResetSubmit) {
            throw new RuntimeException('The ' . self::class . ' constraint is only for use on the ' . PasswordResetSubmit::class . ' class.');
        }

        $resetToken = $this->repository->findOneBy(['token' => $value->getCode()]);
        if (!$resetToken || $resetToken->getExpirationDate() < new DateTime()) {
            return $this->context->buildViolation($constraint->resetTokenMessage)
                ->atPath('code')
                ->addViolation();
        }

        $providedEmail = strtolower($value->getEmail());
        $resetTokenEmail = strtolower($resetToken->getCreator()->getEmail());

        if ($providedEmail !== $resetTokenEmail) {
            $this->context->buildViolation($constraint->emailMessage)
                ->atPath('email')
                ->addViolation();
        }
    }
}
