<?php

namespace App\AddHash\MinerPanel\Domain\Miner\Repository;

use App\AddHash\MinerPanel\Domain\Miner\Model\MinerType;

interface MinerTypeRepositoryInterface
{
    public function get(int $id): ?MinerType;
}