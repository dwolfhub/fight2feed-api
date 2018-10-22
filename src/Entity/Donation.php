<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use App\Annotation\ActiveAware;
use App\Annotation\NotExpiredAware;
use App\Filter\ActiveFilter;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     attributes={"pagination_items_per_page"=15},
 *     normalizationContext={"groups"={"gettable"}},
 *     itemOperations={
 *          "get",
 *          "put"={
 *              "denormalization_context"={"groups"={"settable"}},
 *              "access_control"="object.getCreator().getId() === user.getId()"
 *          }
 *     },
 *     collectionOperations={
 *          "post",
 *          "get"
 *      }
 * )
 * @ApiFilter(OrderFilter::class, properties={"title", "createdDate", "expirationDate"}, arguments={"orderParameterName"="order"})
 * @ORM\Entity(repositoryClass="App\Repository\DonationRepository")
 * @ActiveAware()
 * @NotExpiredAware()
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
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     * @var User|null
     */
    private $creator;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     * @Groups({"gettable","settable"})
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @Assert\Length(max="1000")
     * @Groups({"gettable","settable"})
     */
    private $description;

    /**
     * @var MediaObject|null
     * @ORM\ManyToOne(targetEntity="App\Entity\MediaObject", fetch="EAGER")
     * @ORM\JoinColumn(nullable=true)
     * @ApiProperty(iri="http://schema.org/image")
     * @Groups({"gettable","settable"})
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
     * @Assert\DateTime()
     * @Groups({"gettable","settable"})
     */
    private $expirationDate;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"gettable","settable"})
     * @var bool
     */
    private $active;

    public function __construct()
    {
        $this->setCreatedDate(new DateTime());
        $this->active = false;
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

    public function getCreatedDate(): ?DateTimeInterface
    {
        return $this->createdDate;
    }

    public function setCreatedDate(DateTimeInterface $createdDate): self
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    public function getExpirationDate(): ?DateTimeInterface
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(DateTimeInterface $expirationDate): self
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getCreator(): ?User
    {
        return $this->creator;
    }

    /**
     * @param User|null $creator
     */
    public function setCreator(?User $creator): void
    {
        $this->creator = $creator;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }
}
