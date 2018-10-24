<?php

namespace App\AddHash\AdminPanel\Domain\Miners\Repository;

interface MinerAllowedUrlRepositoryInterface
{
    public function getByValuesEnabledUrl(array $values): array;
}