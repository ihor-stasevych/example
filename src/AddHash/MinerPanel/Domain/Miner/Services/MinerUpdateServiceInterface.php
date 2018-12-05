<?php

namespace App\AddHash\MinerPanel\Domain\Miner\Services;

use App\AddHash\MinerPanel\Domain\Miner\Command\MinerUpdateCommandInterface;

interface MinerUpdateServiceInterface
{
    public function execute(MinerUpdateCommandInterface $command): array;
}