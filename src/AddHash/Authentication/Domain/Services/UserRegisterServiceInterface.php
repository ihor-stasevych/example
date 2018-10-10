<?php

namespace App\AddHash\Authentication\Domain\Services;

use App\AddHash\Authentication\Application\Command\UserRegisterCommand;

interface UserRegisterServiceInterface
{
    public function execute(UserRegisterCommand $command): array;
}