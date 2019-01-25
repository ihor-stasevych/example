<?php

namespace App\AddHash\MinerPanel\Domain\Miner\Exceptions;

class MinerPoolCreateInvalidSSHAuthException extends \Exception
{
    protected $code = 400;
}