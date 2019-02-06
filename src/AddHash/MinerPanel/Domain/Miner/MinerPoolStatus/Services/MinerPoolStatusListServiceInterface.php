<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerPoolStatus\Services;

use App\AddHash\MinerPanel\Domain\Miner\MinerPoolStatus\Command\MinerPoolStatusListCommandInterface;

interface MinerPoolStatusListServiceInterface
{
    public function execute(MinerPoolStatusListCommandInterface $command): array;
}