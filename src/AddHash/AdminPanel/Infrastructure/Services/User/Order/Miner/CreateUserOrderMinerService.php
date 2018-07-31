<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\Order\Miner;


use App\AddHash\AdminPanel\Domain\Miners\Repository\MinerRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Item\StoreOrderItem;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderException;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderRepositoryInterface;
use App\AddHash\AdminPanel\Domain\User\Order\UserOrderMiner;
use App\AddHash\AdminPanel\Domain\User\Order\UserOrderMinerRepositoryInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Order\Miner\CreateUserOrderMinerServiceInterface;

class CreateUserOrderMinerService implements CreateUserOrderMinerServiceInterface
{
	private $orderRepository;
	private $orderMinerRepository;
	private $minerRepository;

	public function __construct(
		StoreOrderRepositoryInterface $orderRepository,
		UserOrderMinerRepositoryInterface $orderMinerRepository,
		MinerRepositoryInterface $minerRepository
	)
	{
		$this->orderRepository = $orderRepository;
		$this->minerRepository = $minerRepository;
		$this->orderMinerRepository = $orderMinerRepository;
	}

	/**
	 * @param StoreOrder $storeOrder
	 * @return bool
	 * @throws StoreOrderException
	 */
	public function execute(StoreOrder $storeOrder)
	{
		if ($storeOrder->getState() != StoreOrder::STATE_PAYED) {
			throw new StoreOrderException('Order does not payed yet: ' . $storeOrder->getId());
		}

		$endPeriod = new \DateTime();
		$userMinerOrder = new UserOrderMiner(
			$storeOrder, $endPeriod->modify('+1 month')
		);

		/** @var $item StoreOrderItem $item */
		foreach ($storeOrder->getItems() as $item) {
			$miners = $item->getProduct()->ensureReservedMiner($item->getQuantity());
			$userMinerOrder->setMiners($miners);
		}

		$this->orderMinerRepository->save($userMinerOrder);

		return true;
	}
}