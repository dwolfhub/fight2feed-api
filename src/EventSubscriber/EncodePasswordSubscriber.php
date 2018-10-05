<?php

namespace App\EventSubscriber;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class EncodePasswordSubscriber
 * @package App\EventSubscriber
 */
class EncodePasswordSubscriber implements EventSubscriberInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * EncodePasswordSubscriber constructor.
     *
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @param GenericEvent $event
     */
    public function onPreNewAndEdit(GenericEvent $event)
    {
        $entity = $event->getSubject();
        if ($entity['class'] !== User::class) {
            return;
        }

        /** @var Request $request */
        $request = $event->getArgument('request');
        $user = $request->request->get('user');
        if (empty($user['password'])) {
            return;
        }

        $encodedPassword = $this->encoder->encodePassword(new User(), $user['password']);
        $user['password'] = $encodedPassword;

        $request->request->set('user', $user);
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            EasyAdminEvents::PRE_EDIT => 'onPreNewAndEdit',
            EasyAdminEvents::PRE_NEW  => 'onPreNewAndEdit',
        ];
    }
}
