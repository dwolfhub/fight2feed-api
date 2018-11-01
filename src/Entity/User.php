<?php

// src/Entity/User.php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\GetMeAction;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
 *     collectionOperations={
 *          "special"={
 *              "method"="GET",
 *              "path"="/users/me",
 *              "controller"=GetMeAction::class,
 *              "normalization_context"={"groups"={"gettable"}}
 *          },
 *     }
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
     * @var string
     *
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
     * @var bool
     *
     * @Groups({"gettable"})
     */
    private $isActive = true;

    /**
     * @ORM\Column(name="is_onboarded", type="boolean")
     * @var bool
     *
     * @Groups({"gettable","settable"})
     */
    private $isOnboarded = false;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Address", mappedBy="user", orphanRemoval=true)
     * @ApiProperty(iri="http://schema.org/image")
     * @var Address[]
     *
     * @Groups({"gettable"})
     */
    private $addresses;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MediaObject", mappedBy="creator")
     * @var MediaObject[]
     */
    private $mediaObjects;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ResetToken", mappedBy="creator", orphanRemoval=true)
     */
    private $resetTokens;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->addresses = new ArrayCollection();
        $this->mediaObjects = new ArrayCollection();
        $this->resetTokens = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return $this
     */
    public function setUsername(string $username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param string $role
     *
     * @return $this
     */
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

    /**
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     *
     * @return $this
     */
    public function setIsActive(bool $isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     *
     */
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

    /**
     * @return null|string
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     */
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
     * @param UserInterface $user
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

    /**
     * @return Collection|Address[]
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    /**
     * @param Address $address
     *
     * @return User
     */
    public function addAddress(Address $address): self
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses[] = $address;
            $address->setUser($this);
        }

        return $this;
    }

    /**
     * @param Address $address
     *
     * @return User
     */
    public function removeAddress(Address $address): self
    {
        if ($this->addresses->contains($address)) {
            $this->addresses->removeElement($address);
            // set the owning side to null (unless already changed)
            if ($address->getUser() === $this) {
                $address->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ResetToken[]
     */
    public function getResetTokens(): Collection
    {
        return $this->resetTokens;
    }

    public function addResetToken(ResetToken $resetToken): self
    {
        if (!$this->resetTokens->contains($resetToken)) {
            $this->resetTokens[] = $resetToken;
            $resetToken->setCreator($this);
        }

        return $this;
    }

    public function removeResetToken(ResetToken $resetToken): self
    {
        if ($this->resetTokens->contains($resetToken)) {
            $this->resetTokens->removeElement($resetToken);
            // set the owning side to null (unless already changed)
            if ($resetToken->getCreator() === $this) {
                $resetToken->setCreator(null);
            }
        }

        return $this;
    }
}
