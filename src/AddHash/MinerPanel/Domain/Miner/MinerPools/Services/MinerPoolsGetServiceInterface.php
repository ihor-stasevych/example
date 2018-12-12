<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerPools\Services;

use App\AddHash\MinerPanel\Domain\Miner\MinerPools\Command\MinerPoolsGetCommandInterface;

interface MinerPoolsGetServiceInterface
{
    public function execute(MinerPoolsGetCommandInterface $command): array;
}