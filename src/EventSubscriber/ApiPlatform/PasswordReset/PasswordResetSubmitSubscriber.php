<?php

namespace App\EventSubscriber\ApiPlatform\PasswordReset;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\DTO\PasswordResetSubmit;
use App\Entity\PasswordResetCode;
use App\Repository\PasswordResetCodeRepository;
use App\Repository\UserRepository;
use DateInterval;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use RandomLib\Factory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class PasswordResetRequestSubscriber
 * @package App\EventSubscriber
 */
class PasswordResetSubmitSubscriber implements EventSubscriberInterface
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
     * @var PasswordResetCodeRepository
     */
    private $resetTokenRepository;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * PasswordResetRequestSubscriber constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     * @param PasswordResetCodeRepository $resetTokenRepository
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        PasswordResetCodeRepository $resetTokenRepository,
        UserPasswordEncoderInterface $encoder
    ) {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->resetTokenRepository = $resetTokenRepository;
        $this->encoder = $encoder;
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

        /** @var PasswordResetSubmit $passwordResetRequest */
        $passwordResetRequest = $event->getControllerResult();
        if (!$passwordResetRequest instanceof PasswordResetSubmit) {
            return;
        }

        $token = $this->resetTokenRepository->findOneBy(['token' => $passwordResetRequest->getCode()]);
        if (!$token) {
            throw new BadRequestHttpException("Invalid password reset code.");
        }

        $user = $token->getCreator();
        $user->setPassword(
            $this->encoder->encodePassword($user, $passwordResetRequest->getPassword())
        );

        $this->entityManager->persist($user);
        $this->entityManager->remove($token);
        $this->entityManager->flush();

        $event->setResponse(new JsonResponse(null, 204));
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

        $resetToken = new PasswordResetCode();
        $resetToken->setCreator($user);
        $resetToken->setExpirationDate($expirationDate);
        $resetToken->setCode($token);

        $this->entityManager->persist($resetToken);
        $this->entityManager->flush();

        return $token;
    }
}
