<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\DTO\PasswordReset;
use App\Entity\ResetToken;
use App\Repository\UserRepository;
use DateInterval;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use RandomLib\Factory;
use Swift_Mailer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

/**
 * Class ResetRequestSubscriber
 * @package App\EventSubscriber
 */
class ResetRequestSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var Swift_Mailer
     */
    private $mailer;
    /**
     * @var string
     */
    private $fromEmail;
    /**
     * @var string
     */
    private $fromName;

    /**
     * ResetRequestSubscriber constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     * @param Environment $twig
     * @param Swift_Mailer $mailer
     * @param string $fromEmail
     * @param string $fromName
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        Environment $twig,
        Swift_Mailer $mailer,
        string $fromEmail,
        string $fromName
    ) {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->twig = $twig;
        $this->mailer = $mailer;
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['onKernelView', EventPriorities::POST_VALIDATE],
        ];
    }

    /**
     * @param GetResponseForControllerResultEvent $event
     *
     * @throws \Exception
     */
    public function onKernelView(GetResponseForControllerResultEvent $event)
    {

        /** @var PasswordReset $resetRequest */
        $resetRequest = $event->getControllerResult();
        if (!$resetRequest instanceof PasswordReset) {
            return;
        }

        $event->setResponse(new JsonResponse(null, 204));

        $user = $this->userRepository->findOneBy(['email' => $resetRequest->getEmail()]);
        if (!$user) {
            return;
        }

        $resetToken = $this->saveResetToken($user);
        $templateData = ['token' => $resetToken];

        $message = new \Swift_Message('Forgot Password Request for the Fight2Feed App');
        $message
            ->setBody(
                $this->twig->render('email/reset-password.html.twig', $templateData),
                'text/html'
            )
            ->addPart(
                $this->twig->render('email/reset-password.txt.twig', $templateData),
                'text/plain'
            )
            ->setFrom($this->fromEmail, $this->fromName)
            ->setTo($user->getEmail());

        $this->mailer->send($message);
    }

    /**
     * @param $user
     *
     * @return string
     *
     * @throws \Exception
     */
    private function saveResetToken($user): string
    {
        $randomFactory = new Factory();
        $generator = $randomFactory->getHighStrengthGenerator();
        $token = $generator->generateString(6, '0123456789');

        $expirationDate = new DateTime('now', new DateTimeZone('UTC'));
        $expirationDate->add(new DateInterval('PT3H')); // plus 3 hours

        $resetToken = new ResetToken();
        $resetToken->setCreator($user);
        $resetToken->setExpirationDate($expirationDate);
        $resetToken->setToken($token);

        $this->entityManager->persist($resetToken);
        $this->entityManager->flush();

        return $token;
    }
}
