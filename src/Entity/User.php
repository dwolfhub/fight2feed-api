<?php

// src/Entity/User.php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Annotations\Annotation\Required;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ApiResource
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("username")
 * @UniqueEntity("email")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     * @Required()
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=25)
     * @Required()
     */
    private $role;

    /**
     * @ORM\Column(type="string", length=64)
     * @Required()
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=254, unique=true)
     * @Required()
     */
    private $email;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     * @Required()
     */
    private $isActive;

    /**
     * @var bool
     * @ORM\Column(name="is_onboarded", type="boolean")
     */
    private $isOnboarded = false;

    public function __construct()
    {
        $this->isActive = true;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername(string $username)
    {
        $this->username = $username;

        return $this;
    }

    public function getSalt()
    {
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole(string $role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Used by Security.
     */
    public function getRoles()
    {
        return [$this->getRole()];
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password,
        ]);
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->password) = unserialize($serialized, ['allowed_classes' => false]);
    }

    /**
     * @return bool
     */
    public function isOnboarded(): bool
    {
        return $this->isOnboarded;
    }

    /**
     * @param bool $isOnboarded
     */
    public function setIsOnboarded(bool $isOnboarded): void
    {
        $this->isOnboarded = $isOnboarded;
    }
}
