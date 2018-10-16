<?php

namespace App\AddHash\AdminPanel\Domain\User\Command\PasswordRecovery;

interface UserPasswordRecoveryHashCommandInterface
{
	public function getHash();
}