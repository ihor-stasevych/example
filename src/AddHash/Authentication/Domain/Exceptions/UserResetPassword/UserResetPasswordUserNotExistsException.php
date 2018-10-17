<?php

namespace App\AddHash\Authentication\Domain\Exceptions\UserResetPassword;

class UserResetPasswordUserNotExistsException extends \Exception
{
    protected $code = 400;
}