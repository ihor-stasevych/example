<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerType\Repository;

use App\AddHash\MinerPanel\Domain\Miner\MinerType\Model\MinerType;

interface MinerTypeRepositoryInterface
{
    public function get(int $id): ?MinerType;

    public function all(): array;
}