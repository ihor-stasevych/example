<?php

namespace App\AddHash\AdminPanel\Domain\Wallet\Exceptions;

class WalletIsExistException extends \Exception
{
	protected $code = 400;
}