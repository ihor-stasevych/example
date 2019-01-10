<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerCalcIncome\Algorithms;

interface MinerCalcIncomeAlgorithmInterface
{
    public function calculate(float $hashRate, float $difficulty, float $reward, int $time): float;
}