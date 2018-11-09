<?php

namespace App\AddHash\Authentication\Domain\Services;

use App\AddHash\Authentication\Domain\Model\User;

interface UserSendPasswordUpdateServiceInterface
{
    public function execute(User $user, array $userDetails = []): void;
}