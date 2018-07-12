<?php

namespace App\AddHash\AdminPanel\Domain\Store\Product\Services;

use App\AddHash\AdminPanel\Domain\Store\Product\Command\StoreProductCreateCommandInterface;

interface StoreProductCreateServiceInterface
{
	public function execute(StoreProductCreateCommandInterface $command);
}