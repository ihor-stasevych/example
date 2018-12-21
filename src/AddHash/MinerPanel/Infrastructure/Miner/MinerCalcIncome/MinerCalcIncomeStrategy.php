<?php

namespace App\AddHash\MinerPanel\Infrastructure\Miner\MinerCalcIncome;

use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin\MinerCoin;
use App\AddHash\MinerPanel\Domain\Miner\MinerCalcIncome\MinerCalcIncomeStrategyInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerCalcIncome\Algorithms\MinerCalcIncomeAlgorithmInterface;

final class MinerCalcIncomeStrategy implements MinerCalcIncomeStrategyInterface
{
    private $algorithm;

    public function __construct(MinerCalcIncomeAlgorithmInterface $algorithm)
    {
        $this->algorithm = $algorithm;
    }

    public function execute(float $hashRate, int $time, MinerCoin $coin): array
    {
        $income = $this->algorithm->calculate($hashRate, $coin->getDifficulty(), $coin->getReward(), $time);

        return [
            'name'   => $coin->getName(),
            'tag'    => $coin->getTag(),
            'income' => $income,
        ];
    }
}