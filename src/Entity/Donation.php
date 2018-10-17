<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use DateTimeInterface;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"gettable"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\DonationRepository")
 */
class Donation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"gettable"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     * @Groups({"gettable"})
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @Assert\Length(max="1000")
     * @Groups({"gettable"})
     */
    private $description;

    /**
     * @var MediaObject|null
     * @ORM\ManyToOne(targetEntity="App\Entity\MediaObject", fetch="EAGER")
     * @ORM\JoinColumn(nullable=true)
     * @ApiProperty(iri="http://schema.org/image")
     * @Groups({"gettable"})
     */
    private $photo;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"gettable"})
     */
    private $createdDate;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     * @Assert\DateTime(format=DateTimeInterface::ISO8601)
     * @Groups({"gettable"})
     */
    private $expirationDate;

    public function __construct()
    {
        $this->setCreatedDate(new DateTime());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPhoto(): ?MediaObject
    {
        return $this->photo;
    }

    public function setPhoto(?MediaObject $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->createdDate;
    }

    public function setCreatedDate(\DateTimeInterface $createdDate): self
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    public function getExpirationDate(): ?\DateTimeInterface
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(\DateTimeInterface $expirationDate): self
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }
}
