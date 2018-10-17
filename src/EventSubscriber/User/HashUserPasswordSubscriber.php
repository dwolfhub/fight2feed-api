<?php

namespace App\EventSubscriber\User;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class HashUserPasswordSubscriber
 * @package App\EventSubscriber\EasyAdmin\User
 */
class HashUserPasswordSubscriber implements EventSubscriberInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * HashUserPasswordSubscriber constructor.
     *
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            EasyAdminEvents::PRE_PERSIST => [
                'easyAdminPreSave',
                -255, // should be last to fire
            ],
            EasyAdminEvents::PRE_UPDATE  => [
                'easyAdminPreSave',
                -255, // should be last to fire
            ],
            KernelEvents::VIEW           => [
                'apiPreWrite',
                EventPriorities::PRE_WRITE,
            ],
        ];
    }

    /**
     * @param GenericEvent $event
     */
    public function easyAdminPreSave(GenericEvent $event)
    {
        /** @var User $user */
        $user = $event->getSubject();
        if (!($user instanceof User)) {
            return;
        }

        $this->hashUserPassword($user);
    }

    /**
     * @param GetResponseForControllerResultEvent $event
     */
    public function apiPreWrite(GetResponseForControllerResultEvent $event)
    {
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$user instanceof User || Request::METHOD_PUT !== $method) {
            return;
        }

        $this->hashUserPassword($user);
        
        return $user;
    }

    /**
     * @param $user
     * @return User
     */
    private function hashUserPassword(User $user): User
    {
        if (
            $user->getId() !== null // not new
            && !$user->getPlainPassword() // not wanting to override
        ) {
            return $user;
        }

        $password = $user->getPassword();
        if ($user->getPlainPassword()) {
            $password = $user->getPlainPassword();
        }

        $encoded = $this->encoder->encodePassword($user, $password);
        $user->setPassword($encoded);

        return $user;
    }
}
