<?php

namespace App\AddHash\AdminPanel\Domain\Store\Order\Services;

use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use App\AddHash\AdminPanel\Domain\Store\Order\Command\StoreOrderCheckoutCommandInterface;

interface StoreOrderCheckoutServiceInterface
{
	public function execute(StoreOrderCheckoutCommandInterface $commandOrder) : StoreOrder;
}