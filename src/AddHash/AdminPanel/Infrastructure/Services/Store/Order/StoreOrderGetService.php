<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Store\Order;

use Psr\Log\LoggerInterface;
use App\AddHash\Authentication\Domain\Model\User;
use Symfony\Component\EventDispatcher\EventDispatcher;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Services\StoreOrderGetServiceInterface;

class StoreOrderGetService implements StoreOrderGetServiceInterface
{
	private $storeOrderRepository;

	private $dispatcher;

	private $logger;

	public function __construct(
		StoreOrderRepositoryInterface $orderRepository,
		EventDispatcher $dispatcher,
		LoggerInterface $logger
	)
	{
		$this->storeOrderRepository = $orderRepository;
		$this->dispatcher = $dispatcher;
		$this->logger = $logger;
	}

	public function execute(User $user)
	{
		$this->logger->info('Try get new order from user: ' . $user->getId());
		return $this->storeOrderRepository->findNewByUserId($user->getId());
	}
}