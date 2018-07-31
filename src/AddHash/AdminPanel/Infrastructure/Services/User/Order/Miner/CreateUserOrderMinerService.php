<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\Order\Miner;


use App\AddHash\AdminPanel\Domain\Miners\Repository\MinerRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderRepositoryInterface;
use App\AddHash\AdminPanel\Domain\User\Order\UserOrderMinerRepositoryInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Order\Miner\CreateUserOrderMinerServiceInterface;

class CreateUserOrderMinerService implements CreateUserOrderMinerServiceInterface
{
	public function __construct(
		StoreOrderRepositoryInterface $orderRepository,
		UserOrderMinerRepositoryInterface $orderMinerRepository,
		MinerRepositoryInterface $minerRepository
	)
	{
	}

	public function execute()
	{

	}
}