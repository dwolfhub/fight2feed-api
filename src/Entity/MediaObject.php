<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Annotation\CurrentUserAware;
use App\Api\CreatableInterface;
use App\Controller\CreateMediaObjectAction;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @ApiResource(
 *     iri="http://schema.org/MediaObject",
 *     normalizationContext={"groups"={"media-gettable"}},
 *     collectionOperations={
 *          "get",
 *          "post"={
 *              "method"="POST",
 *              "controller"=CreateMediaObjectAction::class,
 *              "defaults"={"_api_receive"=false},
 *          },
 *      }
 * )
 * @Vich\Uploadable
 * @CurrentUserAware(userIdField="creator_id")
 */
class MediaObject implements CreatableInterface
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"gettable","media-gettable"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="mediaObjects")
     * @ORM\JoinColumn(nullable=false)
     * @var User|null
     * @Groups({"media-gettable"})
     */
    private $creator;

    /**
     * @var File|null
     * @Assert\NotNull()
     * @Assert\Image(maxSize="2M")
     * @Vich\UploadableField(mapping="media_object", fileNameProperty="contentUrl")
     */
    public $file;

    /**
     * @var string|null
     * @ORM\Column(nullable=true)
     * @ApiProperty(iri="http://schema.org/contentUrl")
     * @Groups({"gettable","media-gettable"})
     */
    public $contentUrl;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return null|File
     */
    public function getFile(): ?File
    {
        return $this->file;
    }

    /**
     * @param null|File $file
     */
    public function setFile(?File $file): void
    {
        $this->file = $file;
    }

    /**
     * @return null|string
     */
    public function getContentUrl(): ?string
    {
        return $this->contentUrl;
    }

    /**
     * @param null|string $contentUrl
     */
    public function setContentUrl(?string $contentUrl): void
    {
        $this->contentUrl = $contentUrl;
    }

    /**
     * @return User
     */
    public function getCreator(): User
    {
        return $this->creator;
    }

    /**
     * @param User $creator
     */
    public function setCreator(User $creator): void
    {
        $this->creator = $creator;
    }
}