<?php

namespace App\AddHash\AdminPanel\Domain\User\Services;

interface MinerControlGetPoolsServiceInterface
{
	public function execute(): array;
}