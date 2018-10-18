<?php

namespace App\AddHash\AdminPanel\Domain\User\Services;

use App\AddHash\AdminPanel\Domain\User\Command\UserCreateCommandInterface;

interface UserCreateServiceInterface
{
	public function execute(UserCreateCommandInterface $command): array;
}