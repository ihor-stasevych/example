<?php

namespace App\AddHash\AdminPanel\Domain\Miners\Repository;

use App\AddHash\AdminPanel\Domain\Miners\MinerPool;

interface MinerPoolRepositoryInterface
{
	public function save(MinerPool $minerPool);

	public function deleteByMinerStock(int $minerStockId): int;
}