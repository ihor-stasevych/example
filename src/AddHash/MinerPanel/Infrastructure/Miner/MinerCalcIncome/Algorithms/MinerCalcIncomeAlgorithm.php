<?php

namespace App\AddHash\MinerPanel\Infrastructure\Miner\MinerCalcIncome\Algorithms;

use App\AddHash\MinerPanel\Domain\Miner\MinerCalcIncome\Algorithms\MinerCalcIncomeAlgorithmInterface;

abstract class MinerCalcIncomeAlgorithm implements MinerCalcIncomeAlgorithmInterface
{
	/**
	 * @param float $hashRate
	 * @param string $difficulty
	 * @param string $reward
	 * @param int $time
	 * @return string
	 */
   abstract public function calculate(float $hashRate, string $difficulty, string $reward, int $time): string;
}