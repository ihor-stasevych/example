<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\Repository;

use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\Model\MinerAlgorithm;

interface MinerAlgorithmRepositoryInterface
{
    public function get(int $id): ?MinerAlgorithm;

    public function all(): array;
}