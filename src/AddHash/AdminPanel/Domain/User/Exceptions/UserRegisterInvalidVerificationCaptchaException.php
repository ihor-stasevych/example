<?php

namespace App\AddHash\AdminPanel\Domain\User\Exceptions;

class UserRegisterInvalidVerificationCaptchaException extends \Exception
{
	protected $code = 400;
}