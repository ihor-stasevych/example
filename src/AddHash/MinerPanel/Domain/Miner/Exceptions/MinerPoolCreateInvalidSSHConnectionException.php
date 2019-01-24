<?php

namespace App\AddHash\MinerPanel\Domain\Miner\Exceptions;

class MinerPoolCreateInvalidSSHConnectionException extends \Exception
{
    protected $code = 400;
}