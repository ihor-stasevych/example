<?php

namespace App\AddHash\AdminPanel\Domain\Wallet\Exceptions;

class WalletTypeIsNotExistException extends \Exception
{
    protected $code = 400;
}