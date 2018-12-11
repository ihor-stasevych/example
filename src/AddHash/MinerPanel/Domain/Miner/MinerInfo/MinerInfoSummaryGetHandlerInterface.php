<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerInfo;

use App\AddHash\MinerPanel\Domain\Miner\Miner;

interface MinerInfoSummaryGetHandlerInterface
{
    public function handler(Miner $miner, bool $updateCache = false): array;
}