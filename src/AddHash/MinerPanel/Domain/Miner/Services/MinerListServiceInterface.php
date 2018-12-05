<?php

namespace App\AddHash\MinerPanel\Domain\Miner\Services;

interface MinerListServiceInterface
{
    public function execute(): array;
}