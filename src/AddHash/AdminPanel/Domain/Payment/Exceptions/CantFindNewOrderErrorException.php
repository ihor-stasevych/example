<?php

namespace App\AddHash\AdminPanel\Domain\Payment\Exceptions;

class CantFindNewOrderErrorException extends \Exception
{
    protected $code = 400;
}