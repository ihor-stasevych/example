<?php

namespace App\AddHash\MinerPanel\Infrastructure\Miner\MinerCalcIncome\Algorithms;

use App\AddHash\MinerPanel\Domain\Miner\MinerCalcIncome\Algorithms\MinerCalcIncomeAlgorithmInterface;

final class MinerCalcIncomeAlgorithmEtHash implements MinerCalcIncomeAlgorithmInterface
{
    public function execute(float $hashRate, string $difficulty, string $reward, int $time): string
    {
        return ($hashRate / $difficulty) * $reward * pow(10, 12) * $time;
    }
}