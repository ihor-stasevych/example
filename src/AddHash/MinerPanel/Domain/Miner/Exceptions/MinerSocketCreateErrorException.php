<?php

namespace App\AddHash\MinerPanel\Domain\Miner\Exceptions;

class MinerSocketCreateErrorException extends \Exception
{
    protected $code = 400;
}