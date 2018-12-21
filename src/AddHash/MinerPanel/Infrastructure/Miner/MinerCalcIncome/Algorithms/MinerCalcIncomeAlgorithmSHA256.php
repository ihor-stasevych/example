<?php

namespace App\AddHash\MinerPanel\Infrastructure\Miner\MinerCalcIncome\Algorithms;

class MinerCalcIncomeAlgorithmSHA256 extends MinerCalcIncomeAlgorithm
{
	/**
	 * @param float $hashRate
	 * @param string $difficulty
	 * @param string $reward
	 * @param int $time
	 * @return string
	 */
	public function calculate(float $hashRate, string $difficulty, string $reward, int $time): string
	{
		return ($time * $reward * pow(10, 12) * $hashRate) / ($difficulty * pow(2, 32));
	}
}