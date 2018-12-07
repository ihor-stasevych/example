<?php

namespace App\AddHash\MinerPanel\Domain\Miner\Exceptions;

class MinerSocketConnectionErrorException extends \Exception
{
    protected $code = 400;
}