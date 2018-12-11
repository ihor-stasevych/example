<?php

namespace App\AddHash\MinerPanel\Domain\Miner\Services;

use App\AddHash\MinerPanel\Domain\Miner\Command\MinerDeleteCommandInterface;

interface MinerDeleteServiceInterface
{
    public function execute(MinerDeleteCommandInterface $command): void;
}