<?php

namespace App\AddHash\Authentication\Domain\Exceptions\UserRegister;

class UserPasswordUpdateInvalidInputDataException extends \Exception
{
    protected $code = 400;
}