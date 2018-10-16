<?php

namespace App\AddHash\AdminPanel\Domain\User\Command\PasswordRecovery;

interface UserPasswordRecoveryCommandInterface
{
	public function getHash();

	public function getPassword();

	public function getConfirmPassword();
}