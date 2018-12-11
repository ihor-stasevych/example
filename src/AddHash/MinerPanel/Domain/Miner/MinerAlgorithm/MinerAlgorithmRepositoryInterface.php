<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm;

interface MinerAlgorithmRepositoryInterface
{
    public function get(int $id): ?MinerAlgorithm;

    public function all(): array;
}