<?php

namespace App\AddHash\AdminPanel\Domain\Store\Product\Services;

use App\AddHash\AdminPanel\Domain\Store\Product\Command\StoreProductVoteCreateCommandInterface;

interface StoreProductVoteCreateServiceInterface
{
	public function execute(StoreProductVoteCreateCommandInterface $command);
}