<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerType;

interface MinerTypeRepositoryInterface
{
    public function get(int $id): ?MinerType;

    public function all(): array;
}