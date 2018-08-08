<?php

namespace App\AddHash\AdminPanel\Domain\Store\Order\Services;

use App\AddHash\AdminPanel\Domain\Store\Order\Command\StoreOrderCreateCommandInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;

interface StoreOrderCreateServiceInterface
{
	public function execute(StoreOrderCreateCommandInterface $command): StoreOrder;
}