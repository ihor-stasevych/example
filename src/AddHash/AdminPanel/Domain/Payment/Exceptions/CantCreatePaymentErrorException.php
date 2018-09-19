<?php

namespace App\AddHash\AdminPanel\Domain\Payment\Exceptions;

class CantCreatePaymentErrorException extends \Exception
{
    protected $code = 400;
}