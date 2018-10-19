<?php

namespace App\AddHash\AdminPanel\Domain\User\Services\AccountSettings;

use App\AddHash\AdminPanel\Domain\User\Command\AccountSettings\PasswordUpdateCommandInterface;

interface PasswordUpdateServiceInterface
{
	public function execute(PasswordUpdateCommandInterface $command): void;
}