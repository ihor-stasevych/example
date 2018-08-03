<?php

namespace App\AddHash\AdminPanel\Domain\Store\Order\Command;

interface StoreOrderCheckoutCommandInterface
{
	public function getOrder();

	public function getToken();
}