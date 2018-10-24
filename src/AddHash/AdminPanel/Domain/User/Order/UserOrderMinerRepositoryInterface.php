<?php

namespace App\AddHash\AdminPanel\Domain\User\Order;

use App\AddHash\AdminPanel\Domain\User\User;

interface UserOrderMinerRepositoryInterface
{
    public function getByEndPeriod(\DateTime $endPeriod): array;

    public function getByUserAndMinerStockId(User $user, int $minerStockId): ?UserOrderMiner;

	public function save(UserOrderMiner $userOrderMiner);

    public function remove(UserOrderMiner $userOrderMiner);
}