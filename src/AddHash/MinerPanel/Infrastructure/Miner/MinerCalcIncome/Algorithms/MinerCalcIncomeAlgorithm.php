<?php

namespace App\AddHash\MinerPanel\Infrastructure\Miner\MinerCalcIncome\Algorithms;

use App\AddHash\MinerPanel\Domain\Miner\MinerCalcIncome\Algorithms\MinerCalcIncomeAlgorithmInterface;

abstract class MinerCalcIncomeAlgorithm implements MinerCalcIncomeAlgorithmInterface
{
	/**
	 * @param float $hashRate
	 * @param float $difficulty
	 * @param float $reward
	 * @param int $time
	 * @return float
	 */
   abstract public function calculate(float $hashRate, float $difficulty, float $reward, int $time): float;
}