<?php

namespace App\AddHash\MinerPanel\Domain\Miner\Summary;

use App\AddHash\MinerPanel\Domain\Miner\Model\Miner;

interface SummaryGetHandlerInterface
{
    public function handler(Miner $miner, bool $updateCache = false): array;
}