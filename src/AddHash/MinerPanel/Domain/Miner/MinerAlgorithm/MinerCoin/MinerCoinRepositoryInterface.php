<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin;

interface MinerCoinRepositoryInterface
{
    public function getByTag(string $tag): ?MinerCoin;

    public function save(MinerCoin $coin): void;
}