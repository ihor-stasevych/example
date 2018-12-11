<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\Services;

interface MinerAlgorithmListServiceInterface
{
    public function execute(): array;
}