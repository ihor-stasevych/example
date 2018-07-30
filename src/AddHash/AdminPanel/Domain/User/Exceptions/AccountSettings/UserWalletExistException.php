<?php

namespace App\AddHash\AdminPanel\Domain\User\Exceptions\AccountSettings;

class UserWalletExistException extends \Exception
{
	protected $code = 400;
}