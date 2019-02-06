<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerHashRate;

use App\AddHash\MinerPanel\Domain\User\User;

interface MinerHashRateRepositoryInterface
{
    public function getAverageValuesByUser(User $user): array;

    public function saveAll(array $hashRates): void;
}