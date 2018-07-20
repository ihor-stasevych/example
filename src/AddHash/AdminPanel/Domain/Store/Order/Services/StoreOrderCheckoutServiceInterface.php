<?php

namespace App\AddHash\AdminPanel\Domain\Store\Order\Services;


use App\AddHash\AdminPanel\Domain\Store\Order\Command\StoreOrderCheckoutCommandOrderInterface;

interface StoreOrderCheckoutServiceInterface
{
	public function execute(StoreOrderCheckoutCommandOrderInterface $commandOrder);
}