<?php

namespace App\AddHash\AdminPanel\Domain\User\Services\Miner\Pool;

use App\AddHash\AdminPanel\Domain\Miners\MinerStock;
use App\AddHash\AdminPanel\Domain\User\Services\Miner\UserMinerControlServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Command\Miner\Pool\UserMinerControlPoolGetCommandInterface;

interface UserMinerControlPoolGetServiceInterface extends UserMinerControlServiceInterface
{
	public function execute(UserMinerControlPoolGetCommandInterface $command, MinerStock $minerStock): array;
}