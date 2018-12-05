<?php

namespace App\AddHash\MinerPanel\Domain\Miner\Services;

use App\AddHash\MinerPanel\Domain\Miner\Command\MinerCreateCommandInterface;

interface MinerCreateServiceInterface
{
    public function execute(MinerCreateCommandInterface $command): array;
}