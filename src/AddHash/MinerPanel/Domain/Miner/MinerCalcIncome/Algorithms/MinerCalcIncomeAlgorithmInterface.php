<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerCalcIncome\Algorithms;

interface MinerCalcIncomeAlgorithmInterface
{
    public function calculate(float $hashRate, string $difficulty, string $reward, int $time): string;
}