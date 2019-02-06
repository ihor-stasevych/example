<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerPool;

use App\AddHash\MinerPanel\Domain\Miner\Miner;

interface MinerPoolRepositoryInterface
{
    public function saveAll(array $minerPools): void;

    public function deleteByMiner(Miner $miner): void;
}