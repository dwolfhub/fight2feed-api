<?php

namespace App\EventSubscriber\ApiPlatform\MediaObject;

use App\Entity\MediaObject;
use Intervention\Image\Constraint;
use Intervention\Image\ImageManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Vich\UploaderBundle\Event\Event;
use Vich\UploaderBundle\Event\Events;

class ResizePhotosSubscriber implements EventSubscriberInterface
{
    private const SIZES = [
        [500, 500],
    ];

    /**
     * @var ImageManager
     */
    private $imageManager;

    public function __construct(ImageManager $imageManager)
    {
        $this->imageManager = $imageManager;
    }

    public function onPostUpload(Event $event)
    {
        /** @var MediaObject $object */
        $object = $event->getObject();

        if (!$object instanceof MediaObject) {
            return null;
        }

        $image = $this->imageManager->make($object->getFile());

        $image->orientate();
        $image->save();

        foreach (self::SIZES as $size) {
            $image->fit($size[0], $size[1], function (Constraint $constraint) {
                $constraint->upsize();
            });
            $image->save(
                $image->dirname . '/'
                . preg_replace('/(\.[^.]+)$/', sprintf('-%d-%d$1', $size[0], $size[1]), $image->basename)
            );
        }

        return $event;
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::POST_UPLOAD => 'onPostUpload',
        ];
    }
}
