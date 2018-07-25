<?php

namespace App\AddHash\AdminPanel\Domain\Store\Order\Command;

interface StoreOrderCheckoutCommandOrderInterface
{
	public function getOrder();

	public function getToken();
}