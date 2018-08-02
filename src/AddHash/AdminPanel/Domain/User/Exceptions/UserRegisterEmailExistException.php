<?php

namespace App\AddHash\AdminPanel\Domain\User\Exceptions;

class UserRegisterEmailExistException extends \Exception
{
	protected $code = 400;
}