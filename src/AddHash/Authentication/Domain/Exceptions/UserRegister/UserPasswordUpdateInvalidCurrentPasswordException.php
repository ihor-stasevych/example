<?php

namespace App\AddHash\Authentication\Domain\Exceptions\UserRegister;

class UserPasswordUpdateInvalidCurrentPasswordException extends \Exception
{
    protected $code = 400;
}