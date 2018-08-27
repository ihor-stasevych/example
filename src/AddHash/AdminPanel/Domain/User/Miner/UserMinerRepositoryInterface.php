<?php

namespace App\AddHash\AdminPanel\Domain\User\Miner;


use App\AddHash\AdminPanel\Domain\Miners\MinerStock;

interface UserMinerRepositoryInterface
{
	public function getSummary(MinerStock $minerStock);
}