<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerCalcIncome;

use App\AddHash\MinerPanel\Domain\Miner\Miner;

interface MinerCalcIncomeHandlerInterface
{
    public function handler(Miner $miner, int $time = 86400): array;
}