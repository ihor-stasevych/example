<?php

namespace App\AddHash\AdminPanel\Domain\Store\Order;

interface StoreOrderRepositoryInterface
{
	public function findById($id);

    public function getNewByTime(\DateTime $updatedAt);

    public function getOrdersPaidByUserId(int $userId): array;

    public function getOrderPaidByIdAndUserId(int $id, int $userId): ?StoreOrder;

    public function save(StoreOrder $order);

    public function findNewByUserId($userId);
}