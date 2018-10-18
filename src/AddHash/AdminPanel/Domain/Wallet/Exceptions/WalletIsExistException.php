<?php

namespace App\AddHash\AdminPanel\Domain\Wallet\Exceptions;

use App\AddHash\System\GlobalContext\Exceptions\Error406Exception;

class WalletIsExistException extends Error406Exception
{
	protected $code = 406;
}