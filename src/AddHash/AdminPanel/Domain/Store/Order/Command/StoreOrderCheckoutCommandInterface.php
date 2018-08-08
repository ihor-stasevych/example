<?php

namespace App\AddHash\AdminPanel\Domain\Store\Order\Command;

interface StoreOrderCheckoutCommandInterface
{
	public function getToken();
}