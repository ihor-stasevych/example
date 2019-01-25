<?php

namespace App\AddHash\MinerPanel\Domain\Miner\Exceptions;

class MinerPoolCreateInvalidSCPFetchException extends \Exception
{
    protected $code = 400;
}