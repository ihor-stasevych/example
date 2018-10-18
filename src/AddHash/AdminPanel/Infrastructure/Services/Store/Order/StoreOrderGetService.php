<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Store\Order;

use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderTransformer;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Services\StoreOrderGetServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Services\UserGetAuthenticationServiceInterface;

class StoreOrderGetService implements StoreOrderGetServiceInterface
{
	private $storeOrderRepository;

    private $authenticationService;

	public function __construct(
		StoreOrderRepositoryInterface $orderRepository,
        UserGetAuthenticationServiceInterface $authenticationService
	)
	{
		$this->storeOrderRepository = $orderRepository;
        $this->authenticationService = $authenticationService;
	}

	public function execute(): array
	{
        $user = $this->authenticationService->execute();
        $order = $this->storeOrderRepository->findNewByUserId($user->getId());
        $result = [];

        if (null !== $order) {
            $result = (new StoreOrderTransformer())->transform($order);
        }

        return $result;
	}
}