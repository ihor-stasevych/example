<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Store\Order;

use App\AddHash\AdminPanel\Domain\Payment\PaymentInterface;
use App\AddHash\AdminPanel\Domain\Payment\Services\MakePaymentForOrderServiceInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Command\StoreOrderCheckoutCommandOrderInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\OrderRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Services\StoreOrderCheckoutServiceInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderException;
use App\AddHash\AdminPanel\Infrastructure\Repository\Store\Order\StoreOrderRepository;

class StoreOrderCheckoutService implements StoreOrderCheckoutServiceInterface
{
	private $orderRepository;
	private $payment;

	public function __construct(
		OrderRepositoryInterface $orderRepository,
		MakePaymentForOrderServiceInterface $makePaymentForOrderService
	)
	{
		$this->orderRepository = $orderRepository;
		$this->payment = $makePaymentForOrderService;
	}

	/**
	 * @param StoreOrderCheckoutCommandOrderInterface $commandOrder
	 * @return bool
	 * @throws StoreOrderException
	 */
	public function execute(StoreOrderCheckoutCommandOrderInterface $commandOrder)
	{
		/** @var StoreOrder $order */
		$order = $this->orderRepository->findById($commandOrder->getOrder());

		if (!$order) {
			throw new StoreOrderException('Cant find order with id: ' . $commandOrder->getOrder());
		}

		if ($order->getState() != StoreOrder::STATE_NEW) {
			throw new StoreOrderException('Order was closed or already payed: ' . $order->getId());
		}

		if (!$order->checkAvailableMiners()) {
			throw new StoreOrderException('Not available product quantity at the moment. Please make new order');
		}

		$payment = $this->payment->execute(
			$commandOrder->getToken(),
			$order->getItemsPriceTotal(),
			$order
		);

		//if ()

		return true;

	}
}