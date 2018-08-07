<?php

namespace App\AddHash\AdminPanel\Domain\Store\Order;

use App\AddHash\AdminPanel\Domain\User\User;

interface StoreOrderRepositoryInterface
{
	public function findById($id);

	#public function findReservedOrder(User $user);

	public function save(StoreOrder $order);

	public function findNewByUserId($userId);
}