<?php

namespace App\AddHash\AdminPanel\Domain\Store\Order;

interface StoreOrderRepositoryInterface
{
	public function findById($id);

    public function getNewByTime(\DateTime $updatedAt);

	public function save(StoreOrder $order);

	public function findNewByUserId($userId);
}