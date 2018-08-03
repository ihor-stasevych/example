<?php

namespace App\AddHash\AdminPanel\Domain\Store\Order\Services;


use App\AddHash\AdminPanel\Domain\Store\Order\Command\StoreOrderCheckoutCommandInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;

interface StoreOrderCheckoutServiceInterface
{
	public function execute(StoreOrderCheckoutCommandInterface $commandOrder) : StoreOrder;
}