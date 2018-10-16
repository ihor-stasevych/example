<?php

namespace App\AddHash\Authentication\Domain\Exceptions\UserRegister;

class UserRegisterInvalidInputDataException extends \Exception
{
    protected $code = 400;
}