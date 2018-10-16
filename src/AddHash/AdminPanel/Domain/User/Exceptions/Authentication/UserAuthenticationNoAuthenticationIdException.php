<?php

namespace App\AddHash\AdminPanel\Domain\User\Exceptions\Authentication;

class UserAuthenticationNoAuthenticationIdException extends \Exception
{
    protected $code = 400;
}