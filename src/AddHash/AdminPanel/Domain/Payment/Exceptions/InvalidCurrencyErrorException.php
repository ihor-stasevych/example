<?php

namespace App\AddHash\AdminPanel\Domain\Payment\Exceptions;

class InvalidCurrencyErrorException extends \Exception
{
    protected $code = 400;
}