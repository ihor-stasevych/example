<?php

namespace App\AddHash\AdminPanel\Domain\Miners\Repository;

interface MinerAllowedUrlRepositoryInterface
{
	public function getCountByValuesEnabledUrl(array $values): int;
}