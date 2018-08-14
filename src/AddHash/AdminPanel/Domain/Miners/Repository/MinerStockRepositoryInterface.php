<?php

namespace App\AddHash\AdminPanel\Domain\Miners\Repository;

use App\AddHash\AdminPanel\Domain\Miners\MinerStock;

interface MinerStockRepositoryInterface
{
	public function save(MinerStock $minerStock);
}