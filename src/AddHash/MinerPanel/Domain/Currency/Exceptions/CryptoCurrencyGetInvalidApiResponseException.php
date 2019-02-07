<?php

namespace App\AddHash\MinerPanel\Domain\Currency\Exceptions;

class CryptoCurrencyGetInvalidApiResponseException extends \Exception
{
    protected $code = 400;
}