<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerCalcIncome;

use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin\MinerCoin;

interface CoinCalcIncomeHandlerInterface
{
    public function handle(MinerCoin $minerCoin, float $hashRate, int $time): float;
}