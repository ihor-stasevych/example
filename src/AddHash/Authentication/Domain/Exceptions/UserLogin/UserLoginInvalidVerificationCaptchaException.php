<?php

namespace App\AddHash\Authentication\Domain\Exceptions\UserLogin;

class UserLoginInvalidVerificationCaptchaException extends \Exception
{
    protected $code = 400;
}