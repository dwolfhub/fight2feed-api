<?php

namespace App\EventSubscriber;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use RandomLib\Factory;
use RandomLib\Generator;
use SecurityLib\Strength;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class CreateUserTempPasswordSubscriber implements EventSubscriberInterface
{
    public function createUserTempPassword(GenericEvent $event)
    {
        /** @var User $user */
        $user = $event->getSubject();
        if (!($user instanceof User)) {
            return;
        }

        if ($user->getPassword()) {
            return;
        }

        $factory = new Factory();
        $generator = $factory->getGenerator(new Strength(Strength::MEDIUM));
        $tempPassword = $generator->generateString(8, Generator::CHAR_ALNUM);

        $user->setPlainPassword($tempPassword);
        $user->setPassword($tempPassword);
    }

    public static function getSubscribedEvents()
    {
        return [
            EasyAdminEvents::PRE_PERSIST => 'createUserTempPassword',
        ];
    }
}
