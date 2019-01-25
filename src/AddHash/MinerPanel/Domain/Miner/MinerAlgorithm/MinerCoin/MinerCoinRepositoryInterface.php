<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin;

use Pagerfanta\Pagerfanta;

interface MinerCoinRepositoryInterface
{
    public function getCoinsWithPagination(?int $currentPage): Pagerfanta;

    public function getByTag(string $tag): ?MinerCoin;

    public function save(MinerCoin $coin): void;
}