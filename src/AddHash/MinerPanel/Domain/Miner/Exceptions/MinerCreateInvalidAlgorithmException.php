<?php

namespace App\AddHash\MinerPanel\Domain\Miner\Exceptions;

class MinerCreateInvalidAlgorithmException extends \Exception
{
    protected $code = 400;
}