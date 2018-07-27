<?php

namespace App\AddHash\AdminPanel\Domain\User\Exceptions\AccountSettings;

class UserWalletIsNotValidException extends \Exception
{
	protected $code = 400;
}