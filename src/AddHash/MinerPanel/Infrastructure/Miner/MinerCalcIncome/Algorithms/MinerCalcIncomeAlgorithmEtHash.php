<?php

namespace App\AddHash\MinerPanel\Infrastructure\Miner\MinerCalcIncome\Algorithms;

class MinerCalcIncomeAlgorithmEtHash extends MinerCalcIncomeAlgorithm
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
		return ($hashRate / $difficulty) * $reward * pow(10, 12) * $time;
	}
}