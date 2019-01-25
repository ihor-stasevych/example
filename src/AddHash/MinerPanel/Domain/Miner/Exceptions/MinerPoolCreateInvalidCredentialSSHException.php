<?php

namespace App\AddHash\MinerPanel\Domain\Miner\Exceptions;

class MinerPoolCreateInvalidCredentialSSHException extends \Exception
{
    protected $code = 400;
}