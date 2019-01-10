<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerPool\Services;

use App\AddHash\MinerPanel\Domain\Miner\MinerPool\Command\MinerPoolGetCommandInterface;

interface MinerPoolGetServiceInterface
{
    public function execute(MinerPoolGetCommandInterface $command): array;
}