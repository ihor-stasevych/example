<?php

namespace App\AddHash\AdminPanel\Domain\Store\Order\Exceptions;

class StoreOrderNoUnPaidErrorException extends \Exception
{
    protected $code = 400;
}