<?php

namespace App\AddHash\AdminPanel\Domain\Store\Order\Services;

interface StoreOrderPrepareCheckoutServiceInterface
{
	public function execute(): array;
}