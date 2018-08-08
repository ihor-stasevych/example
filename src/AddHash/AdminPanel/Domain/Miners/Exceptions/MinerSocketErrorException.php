<?php

namespace App\AddHash\AdminPanel\Domain\Miners\Exceptions;

class MinerSocketErrorException extends \Exception
{
	protected $code = 400;
}