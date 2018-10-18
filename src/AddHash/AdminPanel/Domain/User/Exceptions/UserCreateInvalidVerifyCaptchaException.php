<?php

namespace App\AddHash\AdminPanel\Domain\User\Exceptions;

class UserCreateInvalidVerifyCaptchaException extends \Exception
{
    protected $code = 400;
}