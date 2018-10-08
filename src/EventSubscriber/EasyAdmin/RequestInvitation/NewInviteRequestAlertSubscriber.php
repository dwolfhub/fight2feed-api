<?php

namespace App\EventSubscriber\EasyAdmin\RequestInvitation;

use App\Entity\InvitationRequest;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Twig\Environment;

class NewInviteRequestAlertSubscriber implements EventSubscriberInterface
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
    private $newInviteEmails;

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
        string $newInviteEmails,
        string $fromEmail,
        string $fromName
    ) {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->logger = $logger;
        $this->newInviteEmails = $newInviteEmails;
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName;
    }

    public function onEasyAdminPostPersist(GenericEvent $event)
    {
        /** @var InvitationRequest $inviteRequest */
        $inviteRequest = $event->getSubject();
        if (!($inviteRequest instanceof InvitationRequest)) {
            return;
        }

        try {
            $to = explode(',', $this->newInviteEmails);
            $to = array_map('trim', $to);
            $to = array_filter($to, function ($email) {
                return filter_var($email, FILTER_VALIDATE_EMAIL);
            });

            $message = new \Swift_Message('New Invite Request for the Fight2Feed App');
            $message
                ->setBody(
                    $this->twig->render('email/new-invite-request.html.twig', $inviteRequest),
                    'text/html'
                )
                ->addPart(
                    $this->twig->render('email/new-invite-request.txt.twig', $inviteRequest),
                    'text/plain'
                )
                ->setFrom($this->fromEmail, $this->fromName)
                ->setTo($to);

            $this->mailer->send($message);

        } catch (\Twig_Error $twigError) {
            $this->logger->critical('Twig_Error when rendering new invite request email', [
                'message' => $twigError->getMessage(),
                'code'    => $twigError->getCode(),
                'trace'   => $twigError->getTraceAsString(),
            ]);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            EasyAdminEvents::POST_NEW => 'onEasyAdminPostPersist',
        ];
    }
}
