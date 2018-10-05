<?php

namespace App\EventSubscriber;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class PostUserCreateSubscriber implements EventSubscriberInterface
{
    /**
     * @var string
     */
    private $emails;

    public function __construct(string $newUserEmails)
    {
        $this->emails = $newUserEmails;
    }

    public function onEasyAdminPostNew(GenericEvent $event)
    {
        /** @var User $user */
        $user = $event->getSubject();
        if (!($user instanceof User) || !$user->getId()) {
            return;
        }

        // todo set up email sending via smtp
        // todo send email alert to admins
    }

    public static function getSubscribedEvents()
    {
        return [
            EasyAdminEvents::POST_PERSIST => 'onEasyAdminPostNew',
        ];
    }
}
