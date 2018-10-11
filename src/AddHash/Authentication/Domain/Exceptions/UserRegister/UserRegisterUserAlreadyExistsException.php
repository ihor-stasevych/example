<?php

namespace App\AddHash\Authentication\Domain\Exceptions\UserRegister;

class UserRegisterUserAlreadyExistsException extends \Exception
{
    protected $code = 400;
}