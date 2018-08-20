<?php

namespace App\AddHash\AdminPanel\Domain\User\Exceptions;

class MinerControlPoolNoChangeStatusException extends \Exception
{
	protected $code = 400;
}