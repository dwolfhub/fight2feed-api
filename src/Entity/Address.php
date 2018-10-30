<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\AddressRepository")
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
     * @Groups({"gettable"})
     */
    private $line1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"gettable"})
     */
    private $line2;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"gettable"})
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"gettable"})
     */
    private $state;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"gettable"})
     */
    private $zip;

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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLine1(): ?string
    {
        return $this->line1;
    }

    public function setLine1(string $line1): self
    {
        $this->line1 = $line1;

        return $this;
    }

    public function getLine2(): ?string
    {
        return $this->line2;
    }

    public function setLine2(?string $line2): self
    {
        $this->line2 = $line2;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getZip(): ?string
    {
        return $this->zip;
    }

    public function setZip(string $zip): self
    {
        $this->zip = $zip;

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

}
