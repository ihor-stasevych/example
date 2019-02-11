<?php

namespace App\AddHash\MinerPanel\Infrastructure\Miner\MinerCalcIncome\Algorithms;

class MinerCalcIncomeAlgorithmSHA256 extends MinerCalcIncomeAlgorithm
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
            $result = ($time * $reward * $hashRate * self::ONE_GHASH) / ($difficulty * pow(2, 32));
        }

		return $result;
	}
}