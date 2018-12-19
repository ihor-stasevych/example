<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerInfo;

use App\AddHash\MinerPanel\Domain\Miner\MinerCredential\MinerCredential;

interface MinerInfoSummaryGetHandlerInterface
{
    public function handler(MinerCredential $minerCredential, bool $updateCache = false): array;
}