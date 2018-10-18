<?php

namespace App\AddHash\Authentication\Domain\Services;

use App\AddHash\Authentication\Domain\Command\UserPasswordRecoveryRequestCommandInterface;

interface UserSendResetPasswordEmailServiceInterface
{
    public function execute(UserPasswordRecoveryRequestCommandInterface $command): void;
}