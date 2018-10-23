<?php

namespace App\AddHash\Authentication\Domain\Exceptions\UserLogin;

class UserLoginEmailNotExistsException extends \Exception
{
    protected $code = 400;
}