<?php

namespace App\AddHash\AdminPanel\Domain\User\Services;

use App\AddHash\AdminPanel\Domain\User\Command\PasswordRecovery\UserPasswordRecoveryCommandInterface;

interface UserPasswordRecoveryServiceInterface
{
	public function execute(UserPasswordRecoveryCommandInterface $command);
}