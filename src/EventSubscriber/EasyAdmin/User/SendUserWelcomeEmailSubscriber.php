<?php

namespace App\EventSubscriber\EasyAdmin\User;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Twig\Environment;

class SendUserWelcomeEmailSubscriber implements EventSubscriberInterface
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var string
     */
    private $fromEmail;

    /**
     * @var string
     */
    private $fromName;

    public function __construct(
        \Swift_Mailer $mailer,
        Environment $twig,
        LoggerInterface $logger,
        string $fromEmail,
        string $fromName
    ) {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->logger = $logger;
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName;
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
                ->setFrom($this->fromEmail, $this->fromName)
                ->setTo($user->getEmail());

            $this->mailer->send($message);

        } catch (\Twig_Error $twigError) {
            $this->logger->critical('Twig_Error when rendering user welcome email', [
                'message' => $twigError->getMessage(),
                'code'    => $twigError->getCode(),
                'trace'   => $twigError->getTraceAsString(),
            ]);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            EasyAdminEvents::PRE_PERSIST => 'onEasyAdminPrePersist',
        ];
    }
}
