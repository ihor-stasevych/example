<?php

namespace App\AddHash\AdminPanel\Domain\User\Services;

use App\AddHash\AdminPanel\Domain\User\User;

interface UserGetAuthenticationServiceInterface
{
    public function execute(): User;
}