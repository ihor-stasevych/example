<?php

namespace App\AddHash\Authentication\Domain\Exceptions\UserRegister;

class UserEmailUpdateEmailExistsException extends \Exception
{
    protected $code = 400;
}