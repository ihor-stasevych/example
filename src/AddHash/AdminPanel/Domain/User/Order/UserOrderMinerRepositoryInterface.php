<?php

namespace App\AddHash\AdminPanel\Domain\User\Order;

interface UserOrderMinerRepositoryInterface
{
	public function save(UserOrderMiner $userOrderMiner);
}