<?php

namespace App\Api;

use App\Entity\User;

interface CreatableInterface
{
    public function setCreator(User $user): void;
}