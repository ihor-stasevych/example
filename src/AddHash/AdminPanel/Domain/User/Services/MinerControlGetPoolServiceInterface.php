<?php

namespace App\AddHash\AdminPanel\Domain\User\Services;

use App\AddHash\AdminPanel\Domain\User\Command\MinerControlPoolGetCommandInterface;

interface MinerControlGetPoolServiceInterface
{
	public function execute(MinerControlPoolGetCommandInterface $command): array;
}