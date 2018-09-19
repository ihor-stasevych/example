<?php

namespace App\AddHash\AdminPanel\Domain\Payment\Exceptions;

class CantFindPaymentMethodErrorException extends \Exception
{
    protected $code = 400;
}