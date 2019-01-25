<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin\Command;

interface MinerCoinListCommandInterface
{
    public function page(): ?int;
}