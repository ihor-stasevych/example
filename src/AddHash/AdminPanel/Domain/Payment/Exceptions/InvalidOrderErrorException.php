<?php

namespace App\AddHash\AdminPanel\Domain\Payment\Exceptions;

class InvalidOrderErrorException extends \Exception
{
    protected $code = 400;
}