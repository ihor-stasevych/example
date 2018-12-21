<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin\Services;

use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin\Command\MinerCoinGetCommandInterface;

interface MinerCoinGetServiceInterface
{
    public function execute(MinerCoinGetCommandInterface $command): array;
}