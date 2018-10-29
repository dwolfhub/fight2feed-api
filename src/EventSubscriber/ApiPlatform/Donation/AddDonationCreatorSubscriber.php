<?php

namespace App\EventSubscriber\ApiPlatform\Donation;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Donation;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\Security\Core\Security;

/**
 * Class AddDonationCreatorSubscriber
 * @package App\EventSubscriber\ApiPlatform\Donation
 */
class AddDonationCreatorSubscriber implements EventSubscriberInterface
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
     * @param GetResponseForControllerResultEvent $event
     */
    public function addDonationCreator(GetResponseForControllerResultEvent $event)
    {
        /** @var Donation $donation */
        $donation = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if (!$donation instanceof Donation || Request::METHOD_POST !== $method) {
            return;
        }

        $user = $this->security->getUser();
        if (!$user instanceof User) {
            return;
        }

        $donation->setCreator($user);
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'kernel.view' => ['addDonationCreator', EventPriorities::PRE_WRITE],
        ];
    }
}
