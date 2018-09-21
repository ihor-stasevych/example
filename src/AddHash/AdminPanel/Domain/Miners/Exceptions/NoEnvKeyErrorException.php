<?php

namespace App\AddHash\AdminPanel\Domain\Miners\Exceptions;

class NoEnvKeyErrorException extends \Exception
{
	protected $code = 400;
}