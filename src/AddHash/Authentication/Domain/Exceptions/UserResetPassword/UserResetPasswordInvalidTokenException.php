<?php

namespace App\AddHash\Authentication\Domain\Exceptions\UserResetPassword;

class UserResetPasswordInvalidTokenException extends \Exception
{
    protected $code = 400;
}