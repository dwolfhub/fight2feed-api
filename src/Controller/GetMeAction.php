<?php

namespace App\Controller;

use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class GetMeAction
 * @package App\Controller
 */
class GetMeAction
{
    /**
     * @param UserInterface $user
     * @param LoggerInterface $logger
     *
     * @throws AccessDeniedException
     * @return User
     */
    public function __invoke(UserInterface $user, LoggerInterface $logger): User
    {
        $logger->warning('warning!!!');
        $logger->error('error!!!');
        $logger->critical('critical!!!');
        if (!$user instanceof User) {
            throw new AccessDeniedException();
        }

        return $user;
    }
}