<?php

namespace App\AddHash\MinerPanel\Domain\Miner\Exceptions;

class MinerListInvalidCommandException extends \Exception
{
    protected $code = 400;
}