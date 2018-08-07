<?php

namespace App\AddHash\AdminPanel\Domain\User\Services;


use App\AddHash\AdminPanel\Domain\User\Command\UserRegisterCommandInterface;
use App\AddHash\AdminPanel\Domain\User\User;

interface UserRegisterServiceInterface
{
	public function execute(UserRegisterCommandInterface $command): User;
}