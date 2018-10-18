<?php

namespace App\AddHash\Authentication\Domain\Exceptions\UserRegister;

class UserEmailUpdateInvalidInputDataException extends \Exception
{
    protected $code = 400;
}