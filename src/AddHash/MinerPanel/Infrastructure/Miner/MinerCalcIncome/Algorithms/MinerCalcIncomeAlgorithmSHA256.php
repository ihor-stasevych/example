<?php

namespace App\AddHash\MinerPanel\Infrastructure\Miner\MinerCalcIncome\Algorithms;

use App\AddHash\MinerPanel\Domain\Miner\MinerCalcIncome\Algorithms\MinerCalcIncomeAlgorithmInterface;

final class MinerCalcIncomeAlgorithmSHA256 implements MinerCalcIncomeAlgorithmInterface
{
    public function execute(float $hashRate, string $difficulty, string $reward, int $time): string
    {
        return ($time * $reward * pow(10, 12) * $hashRate) / ($difficulty * pow(2, 32));
    }
}