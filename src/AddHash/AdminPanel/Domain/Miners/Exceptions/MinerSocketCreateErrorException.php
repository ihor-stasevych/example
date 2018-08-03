<?php

namespace App\AddHash\AdminPanel\Domain\Miners\Exceptions;

class MinerSocketCreateErrorException extends \Exception
{
	protected $code = 400;
}