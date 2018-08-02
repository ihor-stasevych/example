<?php

namespace App\AddHash\AdminPanel\Domain\Store\Product\Exceptions\Vote;

class RatingIsNotValidException extends VoteException
{
	protected $code = 400;
}