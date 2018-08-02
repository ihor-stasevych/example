<?php

namespace App\AddHash\AdminPanel\Domain\Store\Product\Exceptions\Vote;

class AssessmentIsNotValidException extends VoteException
{
	protected $code = 400;
}