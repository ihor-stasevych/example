<?php

namespace App\AddHash\MinerPanel\Infrastructure\Miner\MinerCalcIncome\Algorithms;

class MinerCalcIncomeAlgorithmEtHash extends MinerCalcIncomeAlgorithm
{
	/**
	 * @param float $hashRate
	 * @param float $difficulty
	 * @param float $reward
	 * @param int $time
	 * @return float
	 */
	public function calculate(float $hashRate, float $difficulty, float $reward, int $time): float
	{
	    $result = 0;

	    if ($difficulty != 0) {
	        $result = ($hashRate / $difficulty) * $reward * pow(10, 12) * $time;
        }

		return $result;
	}
}