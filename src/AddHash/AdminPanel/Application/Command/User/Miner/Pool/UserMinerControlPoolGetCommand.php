<?php

namespace App\AddHash\AdminPanel\Application\Command\User\Miner\Pool;

use App\AddHash\AdminPanel\Domain\User\Command\Miner\Pool\UserMinerControlPoolGetCommandInterface;

final class UserMinerControlPoolGetCommand implements UserMinerControlPoolGetCommandInterface
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