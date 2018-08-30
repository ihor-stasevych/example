<?php

namespace App\AddHash\AdminPanel\Domain\Store\Order;

use App\AddHash\AdminPanel\Domain\User\Order\History\ListParam\Sort;

interface StoreOrderRepositoryInterface
{
	public function findById($id);

    public function getNewByTime(\DateTime $updatedAt);

    public function getOrdersByUserId(int $userId, Sort $sort, $state = null): array;

    public function getOrderByIdAndUserId(int $id, int $userId): ?StoreOrder;

    public function save(StoreOrder $order);

    public function findNewByUserId($userId);
}