<?php

namespace App\AddHash\Authentication\Domain\Services;

use App\AddHash\Authentication\Domain\Command\UserPasswordRecoveryCommandInterface;

interface UserRecoveryPasswordServiceInterface
{
    public function execute(UserPasswordRecoveryCommandInterface $command): void;
}