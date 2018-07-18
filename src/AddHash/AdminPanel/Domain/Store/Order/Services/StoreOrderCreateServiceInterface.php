<?php

namespace App\AddHash\AdminPanel\Domain\Store\Order\Services;

use App\AddHash\AdminPanel\Domain\Store\Order\Command\StoreOrderCreateCommandInterface;

interface StoreOrderCreateServiceInterface
{
	public function execute(StoreOrderCreateCommandInterface $command);
}