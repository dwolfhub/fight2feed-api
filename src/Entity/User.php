<?php

// src/Entity/User.php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     itemOperations={
 *          "get"={
 *              "access_control"="object.getId() == user.getId()",
 *              "normalization_context"={"groups"={"gettable"}}
 *          },
 *          "put"={
 *              "access_control"="object.getId() == user.getId()",
 *              "denormalization_context"={"groups"={"settable"}},
 *              "normalization_context"={"groups"={"gettable"}},
 *              "validation_groups"={"UpdateApi"},
 *          }
 *     },
 *     collectionOperations={}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("username")
 * @UniqueEntity("email")
 */
class User implements UserInterface, EquatableInterface
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     *
     * @Groups({"gettable"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true, nullable=true)
     * @var string
     *
     * @Groups({"gettable","settable"})
     *
     * @Assert\Length(min="8")
     * @Assert\Type(type="alnum")
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=25)
     * @var string
     *
     * @Groups({"gettable"})
     *
     * @Assert\NotBlank(groups={"CreateAdmin","UpdateAdmin"})
     * @Assert\Choice({"ROLE_DONATOR","ROLE_ADMIN","ROLE_ORG"})
     */
    private $role;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @var string
     *
     * @Groups({"settable"})
     *
     * @Assert\Length(min="6")
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=254, unique=true)
     * @var string
     *
     * @Groups({"gettable","settable"})
     *
     * @Assert\NotBlank(groups={"CreateAdmin","UpdateAdmin"})
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=254)
     * @var string
     *
     * @Groups({"gettable","settable"})
     *
     * @Assert\NotBlank(groups={"CreateAdmin","UpdateAdmin"})
     * @Assert\Regex(pattern="/^\d{3}-\d{3}-\d{4}/", message="Please use phone number format XXX-XXX-XXXX.")
     */
    private $phoneNumber;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     *
     * @Groups({"gettable"})
     */
    private $isActive = true;

    /**
     * @ORM\Column(name="is_onboarded", type="boolean")
     *
     * @Groups({"gettable","settable"})
     */
    private $isOnboarded = false;

    public function __construct()
    {
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

    public function getIsActive(): bool
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

    /**
     * @return bool
     */
    public function getIsOnboarded(): bool
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

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    /**
     * @param mixed $phoneNumber
     */
    public function setPhoneNumber($phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * The equality comparison should neither be done by referential equality
     * nor by comparing identities (i.e. getId() === getId()).
     *
     * However, you do not need to compare every attribute, but only those that
     * are relevant for assessing whether re-authentication is required.
     *
     * @return bool
     */
    public function isEqualTo(UserInterface $user)
    {
        if ($user instanceof User) {
            return $user->getRole() === $this->getRole()
                   && $user->getUsername() === $this->getUsername()
                   && $user->getId() === $this->getId()
                   && $user->getIsActive() === $this->getIsActive();
        }

        return false;
    }
}
