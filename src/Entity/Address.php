<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Annotation\CurrentUserAware;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     iri="http://schema.org/PostalAddress",
 *     denormalizationContext={"groups"={"settable"}},
 *     normalizationContext={"groups"={"gettable"}},
 *     itemOperations={
 *          "get"={
 *              "access_control"="object.getUser().getId() === user.getId()"
 *          },
 *          "put"={
 *              "access_control"="object.getUser().getId() === user.getId()"
 *          },
 *          "delete"={
 *              "access_control"="object.getUser().getId() === user.getId()"
 *          },
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\AddressRepository")
 * @CurrentUserAware()
 */
class Address
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"gettable","settable"})
     */
    private $streetAddress;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"gettable","settable"})
     */
    private $addressLocality;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"gettable","settable"})
     */
    private $addressRegion;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"gettable","settable"})
     */
    private $postalCode;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"gettable","settable"})
     */
    private $addressCountry;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="addresses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Donation", mappedBy="address")
     * @ORM\JoinColumn(nullable=false)
     * @var Donation[]
     */
    private $donations;

    public function __construct()
    {
        $this->donations = [];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStreetAddress(): ?string
    {
        return $this->streetAddress;
    }

    public function setStreetAddress(string $streetAddress): self
    {
        $this->streetAddress = $streetAddress;

        return $this;
    }

    public function getAddressLocality(): ?string
    {
        return $this->addressLocality;
    }

    public function setAddressLocality(string $addressLocality): self
    {
        $this->addressLocality = $addressLocality;

        return $this;
    }

    public function getAddressRegion(): ?string
    {
        return $this->addressRegion;
    }

    public function setAddressRegion(string $addressRegion): self
    {
        $this->addressRegion = $addressRegion;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Donation[]
     */
    public function getDonations(): array
    {
        return $this->donations;
    }

    /**
     * @param Donation[] $donations
     */
    public function setDonations(array $donations): void
    {
        $this->donations = $donations;
    }

    /**
     * @return mixed
     */
    public function getAddressCountry()
    {
        return $this->addressCountry;
    }

    /**
     * @param mixed $addressCountry
     */
    public function setAddressCountry($addressCountry): void
    {
        $this->addressCountry = $addressCountry;
    }

}
