<?php

namespace App\AddHash\AdminPanel\Domain\Store\Order\Item;


interface StoreOrderItemRepositoryInterface
{
	public function save(StoreOrderItem $orderItem);
}