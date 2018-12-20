<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerInfo;

use App\AddHash\MinerPanel\Domain\Miner\MinerCredential\MinerCredential;

interface MinerInfoPoolsGetHandlerInterface
{
    public function handler(MinerCredential $minerCredential, bool $updateCache = false): array;
}