<?php

namespace App\AddHash\MinerPanel\Domain\Miner\Repository;

use App\AddHash\MinerPanel\Domain\Miner\Model\MinerAlgorithm;

interface MinerAlgorithmRepositoryInterface
{
    public function get(int $id): ?MinerAlgorithm;
}