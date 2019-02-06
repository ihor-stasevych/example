<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerPoolStatus\Exceptions;

class MinerPoolStatusListInvalidCommandException extends \Exception
{
    protected $code = 400;
}