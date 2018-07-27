<?php

namespace App\AddHash\AdminPanel\Domain\Store\Order\Services;

use App\AddHash\AdminPanel\Domain\Store\Order\Command\StoreOrderAddProductCommandInterface;

interface StoreOrderAddProductServiceInterface
{
	public function execute(StoreOrderAddProductCommandInterface $command);
}