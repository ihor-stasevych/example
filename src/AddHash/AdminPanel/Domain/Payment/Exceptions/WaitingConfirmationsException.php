<?php

namespace App\AddHash\AdminPanel\Domain\Payment\Exceptions;

class WaitingConfirmationsException extends \Exception
{
    protected $code = 400;
}