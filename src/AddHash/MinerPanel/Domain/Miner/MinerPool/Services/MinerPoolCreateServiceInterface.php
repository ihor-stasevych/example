<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerPool\Services;

use App\AddHash\MinerPanel\Domain\Miner\MinerPool\Command\MinerPoolCreateCommandInterface;

interface MinerPoolCreateServiceInterface
{
    public function execute(MinerPoolCreateCommandInterface $command): void;
}