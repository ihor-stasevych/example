<?php

namespace App\AddHash\AdminPanel\Domain\User\Command\PasswordRecovery;

use App\AddHash\System\GlobalContext\ValueObject\Email;

interface UserPasswordRecoveryRequestCommandInterface
{
	public function getEmail(): Email;
}