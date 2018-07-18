<?php

namespace App\AddHash\AdminPanel\Domain\Store\Order;

interface OrderRepositoryInterface
{
	public function findById($id);

	public function save(StoreOrder $order);

	public function findNewByUserId($userId);
}