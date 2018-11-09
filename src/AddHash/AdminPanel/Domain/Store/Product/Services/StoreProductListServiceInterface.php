<?php

namespace App\AddHash\AdminPanel\Domain\Store\Product\Services;

use App\AddHash\AdminPanel\Domain\Store\Product\Command\StoreProductListCommandInterface;

interface StoreProductListServiceInterface
{
	public function execute(StoreProductListCommandInterface $command): array;
}