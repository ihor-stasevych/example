<?php

namespace App\AddHash\AdminPanel\Domain\User\Exceptions\AccountSettings;

class PasswordIsNotValidException extends \Exception
{
	protected $code = 400;
}