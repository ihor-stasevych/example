<?php

namespace App\AddHash\AdminPanel\Domain\Store\Order\Services;

use App\AddHash\AdminPanel\Domain\Store\Order\Command\StoreOrderPrepareCheckoutCommandInterface;

interface StoreOrderPrepareCheckoutServiceInterface
{
	public function execute(StoreOrderPrepareCheckoutCommandInterface $commandOrder);
}