<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Store\Order;

use App\AddHash\AdminPanel\Domain\Store\Order\OrderRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Services\StoreOrderGetServiceInterface;
use App\AddHash\AdminPanel\Domain\User\User;

class StoreOrderGetService implements StoreOrderGetServiceInterface
{

	private $storeOrderRepository;

	public function __construct(
		OrderRepositoryInterface $orderRepository
	)
	{
		$this->storeOrderRepository = $orderRepository;
	}

	public function execute(User $user)
	{
		return $this->storeOrderRepository->findNewByUserId($user->getId());
	}
}