<?php

namespace App\AddHash\AdminPanel\Domain\Store\Product\Services;

use App\AddHash\AdminPanel\Domain\Store\Product\Command\StoreProductGetCommandInterface;

interface StoreProductGetServiceInterface
{
	public function execute(StoreProductGetCommandInterface $command);
}