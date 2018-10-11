<?php

namespace App\AddHash\AdminPanel\Domain\User\Services;

use App\AddHash\AdminPanel\Domain\User\Command\UserRegisterCommandInterface;

interface UserRegisterServiceInterface
{
	public function execute(UserRegisterCommandInterface $command): array;
}