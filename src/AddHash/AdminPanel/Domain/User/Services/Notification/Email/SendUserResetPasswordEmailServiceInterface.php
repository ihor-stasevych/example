<?php

namespace App\AddHash\AdminPanel\Domain\User\Services\Notification\Email;

use App\AddHash\AdminPanel\Domain\User\Command\PasswordRecovery\UserPasswordRecoveryRequestCommandInterface;

interface SendUserResetPasswordEmailServiceInterface
{
	public function execute(UserPasswordRecoveryRequestCommandInterface $command);
}