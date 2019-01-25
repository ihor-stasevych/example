<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin\Exceptions;

class MinerCoinInvalidCommandException extends \Exception
{
    protected $code = 400;
}