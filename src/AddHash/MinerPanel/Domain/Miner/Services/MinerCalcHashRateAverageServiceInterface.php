<?php

namespace App\AddHash\MinerPanel\Domain\Miner\Services;

interface MinerCalcHashRateAverageServiceInterface
{
    public function execute(): void;
}