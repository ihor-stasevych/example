<?php

namespace App\AddHash\AdminPanel\Domain\Miners\Exceptions;

class MinerSocketConnectionErrorException extends MinerSocketErrorException
{
	protected $code = 400;
}