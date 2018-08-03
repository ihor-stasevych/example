<?php

namespace App\AddHash\AdminPanel\Domain\Miners\Exceptions;

class MinerSocketCreateError extends \Exception
{
	protected $code = 400;
}