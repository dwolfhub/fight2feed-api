<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

class GetMeAction
{
    public function __invoke(UserInterface $user): User
    {
        return $user;
    }
}