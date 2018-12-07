<?php

namespace App\AddHash\MinerPanel\Domain\Miner\Exceptions;

class MinerUpdateInvalidAlgorithmException extends \Exception
{
    protected $code = 400;
}