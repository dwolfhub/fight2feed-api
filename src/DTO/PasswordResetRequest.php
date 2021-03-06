<?php

namespace App\DTO;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class PasswordResetRequest
 * @package App\Entity
 * @ApiResource(
 *     itemOperations={},
 *     collectionOperations={"POST"={"path"="/password-reset-request"}}
 * )
 */
final class PasswordResetRequest
{
    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     * @var string
     */
    private $email;

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}