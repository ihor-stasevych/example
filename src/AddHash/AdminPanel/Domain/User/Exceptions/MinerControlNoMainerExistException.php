<?php

namespace App\AddHash\AdminPanel\Domain\User\Exceptions;

class MinerControlNoMainerExistException extends \Exception
{
	protected $code = 400;
}