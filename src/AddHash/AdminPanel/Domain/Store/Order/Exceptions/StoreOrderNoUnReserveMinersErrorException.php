<?php

namespace App\AddHash\AdminPanel\Domain\Store\Order\Exceptions;

class StoreOrderNoUnReserveMinersErrorException extends \Exception
{
    protected $code = 400;
}