<?php

namespace App\EventSubscriber;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class PostUserCreateSubscriber implements EventSubscriberInterface
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var Environment
     */
    private $twig;

    public function __construct(\Swift_Mailer $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function onEasyAdminPrePersist(GenericEvent $event)
    {
        /** @var User $user */
        $user = $event->getSubject();
        if (!($user instanceof User)) {
            return;
        }

        $userTempPassword = $user->getPlainPassword();
        $templateData = [
            'password' => $userTempPassword,
        ];

        try {
            $message = new \Swift_Message('Welcome to the Fight2Feed App');

            $message
                ->setBody(
                    $this->twig->render('email/new-user-temp-password.html.twig', $templateData),
                    'text/html'
                )
                ->addPart(
                    $this->twig->render('email/new-user-temp-password.txt.twig', $templateData),
                    'text/plain'
                )
                ->setFrom('danielrwolf5@gmail.com', 'Daniel Wolf')
                ->setTo($user->getEmail());

            $this->mailer->send($message);

        } catch (\Twig_Error $twigError) {
            // TODO LOG
        }
    }

    public static function getSubscribedEvents()
    {
        return [
//            KernelEvents::REQUEST => 'onEasyAdminPrePersist',
            EasyAdminEvents::PRE_PERSIST => 'onEasyAdminPrePersist',
        ];
    }
}
