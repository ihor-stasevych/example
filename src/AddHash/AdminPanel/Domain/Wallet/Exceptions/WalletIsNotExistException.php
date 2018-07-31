<?php

namespace App\AddHash\AdminPanel\Domain\Wallet\Exceptions;

class WalletIsNotExistException extends \Exception
{
	protected $code = 400;
}