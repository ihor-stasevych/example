<?php

namespace App\AddHash\Authentication\Domain\Exceptions\UserRegister;

class UserRegisterInvalidVerifyCaptchaException extends \Exception
{
    protected $code = 400;
}