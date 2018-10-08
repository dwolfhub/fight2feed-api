<?php

namespace App\EventSubscriber;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class HashUserPasswordSubscriber implements EventSubscriberInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function hashUserPassword(GenericEvent $event)
    {
        /** @var User $user */
        $user = $event->getSubject();
        if (!($user instanceof User)) {
            return;
        }

        if (
            $user->getId() !== null // not new
            && !$user->getPlainPassword() // not wanting to override
        ) {
            return;
        }

        $password = $user->getPassword();
        if ($user->getPlainPassword()) {
            $password = $user->getPlainPassword();
        }

        $encoded = $this->encoder->encodePassword($user, $password);
        $user->setPassword($encoded);

        $event['entity'] = $user;
    }

    public static function getSubscribedEvents()
    {
        return [
            EasyAdminEvents::PRE_PERSIST => [
                'hashUserPassword',
                -255, // should be last to fire
            ],
            EasyAdminEvents::PRE_UPDATE  => [
                'hashUserPassword',
                -255, // should be last to fire
            ],
        ];
    }
}
