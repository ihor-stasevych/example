<?php

namespace App\AddHash\AdminPanel\Domain\User\Exceptions;

class UserRegisterUserNameExistException extends \Exception
{
	protected $code = 400;
}