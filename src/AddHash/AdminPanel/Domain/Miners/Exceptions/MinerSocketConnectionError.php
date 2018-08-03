<?php

namespace App\AddHash\AdminPanel\Domain\Miners\Exceptions;

class MinerSocketConnectionError extends \Exception
{
	protected $code = 400;
}