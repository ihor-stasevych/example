<?php

namespace App\AddHash\AdminPanel\Domain\User\Services\Order\Miner;

use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;

interface CreateUserOrderMinerServiceInterface
{
	public function execute(StoreOrder $storeOrder);
}