<?php

namespace App\AddHash\AdminPanel\Domain\Store\Product\Exceptions\Vote;

class UserVoteExistException extends VoteException
{
	protected $code = 400;
}