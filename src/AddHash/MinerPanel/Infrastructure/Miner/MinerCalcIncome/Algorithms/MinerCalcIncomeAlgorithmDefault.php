<?php

namespace App\AddHash\MinerPanel\Infrastructure\Miner\MinerCalcIncome\Algorithms;

use App\AddHash\MinerPanel\Domain\Miner\MinerCalcIncome\Algorithms\MinerCalcIncomeAlgorithmInterface;

final class MinerCalcIncomeAlgorithmDefault implements MinerCalcIncomeAlgorithmInterface
{
    public function execute(float $hashRate, string $difficulty, string $reward, int $time): string
    {
        return 0;
    }
}