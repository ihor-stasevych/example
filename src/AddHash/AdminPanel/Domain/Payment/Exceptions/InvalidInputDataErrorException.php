<?php

namespace App\AddHash\AdminPanel\Domain\Payment\Exceptions;

class InvalidInputDataErrorException extends \Exception
{
    protected $code = 400;
}