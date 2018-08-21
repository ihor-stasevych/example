<?php

namespace App\AddHash\AdminPanel\Domain\User\Services\Miner\Pool;

use App\AddHash\AdminPanel\Domain\Miners\MinerStock;
use App\AddHash\AdminPanel\Domain\User\Command\Miner\UserMinerControlCommandInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Miner\UserMinerControlServiceInterface;

interface UserMinerControlPoolGetServiceInterface extends UserMinerControlServiceInterface
{
	public function execute(UserMinerControlCommandInterface $command, MinerStock $minerStock): array;
}