<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerType\Services;

interface MinerTypeListServiceInterface
{
    public function execute(): array;
}