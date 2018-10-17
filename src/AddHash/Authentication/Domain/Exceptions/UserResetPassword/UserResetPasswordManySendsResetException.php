<?php

namespace App\AddHash\Authentication\Domain\Exceptions\UserResetPassword;

class UserResetPasswordManySendsResetException extends \Exception
{
    protected $code = 400;
}