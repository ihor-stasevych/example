<?php

namespace App\AddHash\AdminPanel\Domain\Store\Product\Exceptions\Vote;

class ProductIsNotExistException extends VoteException
{
	protected $code = 400;
}