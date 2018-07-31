<?php

namespace App\AddHash\AdminPanel\Domain\Store\Order\Services;


use App\AddHash\AdminPanel\Domain\Store\Order\Command\StoreOrderCheckoutCommandOrderInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;

interface StoreOrderCheckoutServiceInterface
{
	public function execute(StoreOrderCheckoutCommandOrderInterface $commandOrder) : StoreOrder;
}