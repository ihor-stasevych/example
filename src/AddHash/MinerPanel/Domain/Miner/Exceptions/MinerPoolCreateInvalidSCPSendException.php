<?php

namespace App\AddHash\MinerPanel\Domain\Miner\Exceptions;

class MinerPoolCreateInvalidSCPSendException extends \Exception
{
    protected $code = 400;
}