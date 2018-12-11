<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm;

interface MinerAlgorithmRepositoryInterface
{
    public function get(int $id): ?MinerAlgorithm;

    public function getByValue(string $value): ?MinerAlgorithm;

    public function all(): array;

    public function save(MinerAlgorithm $algorithm): void;
}