<?php

namespace App\EventSubscriber\ApiPlatform;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Api\CreatableInterface;
use App\Entity\Donation;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

/**
 * Class AddCreatorSubscriber
 * @package App\EventSubscriber\ApiPlatform\Donation
 */
class AddCreatorSubscriber implements EventSubscriberInterface
{
    /**
     * @var Security
     */
    private $security;

    /**
     * AddCreatorSubscriber constructor.
     *
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @param GetResponseForControllerResultEvent $event
     */
    public function addCreator(GetResponseForControllerResultEvent $event)
    {
        /** @var CreatableInterface $donation */
        $creatable = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if (!$creatable instanceof CreatableInterface || Request::METHOD_POST !== $method) {
            return;
        }

        $user = $this->security->getUser();
        if (!$user instanceof User) {
            return;
        }

        $creatable->setCreator($user);
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['addCreator', EventPriorities::PRE_WRITE],
        ];
    }
}
