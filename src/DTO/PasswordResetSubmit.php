<?php

namespace App\DTO;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Validator\PasswordResetCodeExists;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class PasswordResetSubmit
 * @package App\DTO
 *
 * @ApiResource(
 *     itemOperations={},
 *     collectionOperations={"POST"={"path"="/password-reset-submit"}}
 * )
 * @PasswordResetCodeExists()
 */
class PasswordResetSubmit
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Regex("/^\d{6}$/")
     */
    private $code;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min="6")
     */
    private $password;

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string|null $code
     */
    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }
}