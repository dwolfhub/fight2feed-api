<?php

namespace App\Controller;

use App\Entity\User;
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
     *
     * @throws AccessDeniedException
     * @return User
     */
    public function __invoke(Usernterface $user): User
    {

        if (!$user instanceof User) {
            throw new AccessDeniedException();
        }

        return $user;
    }
}