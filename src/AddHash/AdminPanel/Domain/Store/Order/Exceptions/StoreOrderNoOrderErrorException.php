<?php

namespace App\AddHash\AdminPanel\Domain\Store\Order\Exceptions;

class StoreOrderNoOrderErrorException extends \Exception
{
    protected $code = 400;
}