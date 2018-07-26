<?php

namespace App\AddHash\AdminPanel\Domain\Miners\Repository;


use App\AddHash\AdminPanel\Domain\Miners\Miner;

interface MinerRepositoryInterface
{
	public function save(Miner $miner);
}