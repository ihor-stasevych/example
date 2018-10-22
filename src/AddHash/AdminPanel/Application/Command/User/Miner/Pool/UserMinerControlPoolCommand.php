<?php

namespace App\AddHash\AdminPanel\Application\Command\User\Miner\Pool;

use App\AddHash\AdminPanel\Domain\User\Command\Miner\Pool\UserMinerControlPoolCommandInterface;

final class UserMinerControlPoolCommand implements UserMinerControlPoolCommandInterface
{
	private $minerId;

	public function __construct($minerId)
	{
		$this->minerId = $minerId;
	}

	public function getMinerId(): int
    {
        return $this->minerId;
    }
}