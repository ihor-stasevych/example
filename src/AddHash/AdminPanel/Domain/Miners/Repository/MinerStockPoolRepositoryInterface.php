<?php

namespace App\AddHash\AdminPanel\Domain\Miners\Repository;

use App\AddHash\AdminPanel\Domain\Miners\MinerStock;

interface MinerStockPoolRepositoryInterface
{
	public function saveAll(array $minerStockPools): void;

    public function deleteByMinerStock(MinerStock $minerStock): void;
}