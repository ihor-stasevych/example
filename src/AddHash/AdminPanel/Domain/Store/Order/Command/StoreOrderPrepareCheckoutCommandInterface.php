<?php

namespace App\AddHash\AdminPanel\Domain\Store\Order\Command;

interface StoreOrderPrepareCheckoutCommandInterface
{
	public function getOrder();
}