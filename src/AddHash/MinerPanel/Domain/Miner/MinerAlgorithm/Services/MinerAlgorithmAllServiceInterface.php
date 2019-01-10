<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\Services;

interface MinerAlgorithmAllServiceInterface
{
    public function execute(): array;
}