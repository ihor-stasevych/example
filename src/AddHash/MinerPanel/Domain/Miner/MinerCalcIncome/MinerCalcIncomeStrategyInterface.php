<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerCalcIncome;

use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin\MinerCoin;

interface MinerCalcIncomeStrategyInterface
{
    public function execute(float $hashRate, int $time, MinerCoin $coin): array;
}