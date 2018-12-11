<?php

namespace App\AddHash\MinerPanel\Domain\Miner\Services;

use App\AddHash\MinerPanel\Domain\Miner\Command\MinerGetCommandInterface;

interface MinerGetServiceInterface
{
    public function execute(MinerGetCommandInterface $command): array;
}