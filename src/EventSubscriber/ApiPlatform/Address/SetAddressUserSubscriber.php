<?php

namespace App\EventSubscriber\ApiPlatform\Address;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Address;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\Security\Core\Security;

/**
 * Class SetAddressUserSubscriber
 * @package App\EventSubscriber\ApiPlatform\Address
 */
class SetAddressUserSubscriber implements EventSubscriberInterface
{
    /**
     * @var Security
     */
    private $security;

    /**
     * AddDonationCreatorSubscriber constructor.
     *
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'kernel.view' => ['setAddressUser', EventPriorities::PRE_WRITE],
        ];
    }

    /**
     * @param GetResponseForControllerResultEvent $event
     */
    public function setAddressUser(GetResponseForControllerResultEvent $event)
    {
        /** @var Address $address */
        $address = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if (!$address instanceof Address || $method !== Request::METHOD_POST) {
            return;
        }

        $user = $this->security->getUser();
        if (!$user instanceof User) {
            return;
        }

        $address->setUser($user);
    }
}