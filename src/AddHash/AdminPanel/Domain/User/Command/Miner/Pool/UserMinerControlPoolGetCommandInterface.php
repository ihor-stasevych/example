<?php

namespace App\AddHash\AdminPanel\Domain\User\Command\Miner\Pool;

use App\AddHash\AdminPanel\Domain\User\Command\Miner\UserMinerControlCommandInterface;

interface UserMinerControlPoolGetCommandInterface extends UserMinerControlCommandInterface
{
	public function getMinerId(): int;
}