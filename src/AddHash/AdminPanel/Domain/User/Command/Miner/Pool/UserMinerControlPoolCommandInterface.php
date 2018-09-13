<?php

namespace App\AddHash\AdminPanel\Domain\User\Command\Miner\Pool;

interface UserMinerControlPoolCommandInterface
{
	public function getMinerId(): int;
}