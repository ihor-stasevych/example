<?php

namespace App\AddHash\AdminPanel\Domain\Miners\Repository;

use App\AddHash\AdminPanel\Domain\Miners\MinerAlgorithm;

interface MinerAllowedUrlRepositoryInterface
{
    public function getByValuesAndAlgorithm(MinerAlgorithm $algorithm, array $values): array;
}