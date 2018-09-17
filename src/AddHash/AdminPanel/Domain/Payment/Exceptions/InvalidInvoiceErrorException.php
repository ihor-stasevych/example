<?php

namespace App\AddHash\AdminPanel\Domain\Payment\Exceptions;

class InvalidInvoiceErrorException extends \Exception
{
    protected $code = 400;
}