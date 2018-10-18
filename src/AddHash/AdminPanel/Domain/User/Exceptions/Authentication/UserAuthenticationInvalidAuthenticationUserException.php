<?php

namespace App\AddHash\AdminPanel\Domain\User\Exceptions\Authentication;

class UserAuthenticationInvalidAuthenticationUserException extends \Exception
{
    protected $code = 400;
}