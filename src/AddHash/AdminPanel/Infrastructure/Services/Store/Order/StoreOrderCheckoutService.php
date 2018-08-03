<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Store\Order;

use App\AddHash\AdminPanel\Domain\Payment\Payment;
use App\AddHash\AdminPanel\Domain\Payment\PaymentInterface;
use App\AddHash\AdminPanel\Domain\Payment\Services\MakePaymentForOrderServiceInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Command\StoreOrderCheckoutCommandInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Services\StoreOrderCheckoutServiceInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderException;
use App\AddHash\AdminPanel\Infrastructure\Repository\Store\Order\StoreOrderRepository;

class StoreOrderCheckoutService implements StoreOrderCheckoutServiceInterface
{
	private $orderRepository;
	private $payment;

	public function __construct(
		StoreOrderRepositoryInterface $orderRepository,
		MakePaymentForOrderServiceInterface $makePaymentForOrderService
	)
	{
		$this->orderRepository = $orderRepository;
		$this->payment = $makePaymentForOrderService;
	}

	/**
	 * @param StoreOrderCheckoutCommandInterface $commandOrder
	 * @return StoreOrder
	 * @throws StoreOrderException
	 */
	public function execute(StoreOrderCheckoutCommandInterface $commandOrder): StoreOrder
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

		/** @var Payment $payment */
		$payment = $this->payment->execute(
			$commandOrder->getToken(),
			$order->getItemsPriceTotal(),
			$order->getUser()
		);

		$order->setPayedState();
		$order->setPayment($payment);

		$this->orderRepository->save($order);

		return $order;
	}
}