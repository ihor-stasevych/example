<?php

namespace App\AddHash\AdminPanel\Domain\Store\Order;

use App\AddHash\AdminPanel\Domain\User\Order\History\ListParam\Sort;

interface StoreOrderRepositoryInterface
{
	public function findById(int $id);

    public function findNewByUserId(int $userId): ?StoreOrder;

    public function findNewById(int $id): ?StoreOrder;

    public function getNewByTime(\DateTime $updatedAt): array;

    public function getOrdersByUserId(int $userId, Sort $sort, $state = null): array;

    public function getOrderByIdAndUserId(int $id, int $userId): ?StoreOrder;

    public function save(StoreOrder $order);

    public function remove(StoreOrder $order);
}