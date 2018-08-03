<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Store\Order;

use App\AddHash\AdminPanel\Domain\Store\Order\Command\StoreOrderPrepareCheckoutCommandInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Services\StoreOrderPrepareCheckoutServiceInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderException;
use App\AddHash\AdminPanel\Infrastructure\Payment\Gateway\Stripe\PaymentGatewayStripe;

class StoreOrderPrepareCheckoutService implements StoreOrderPrepareCheckoutServiceInterface
{
	private $orderRepository;

	public function __construct(
		StoreOrderRepositoryInterface $orderRepository
	)
	{
		$this->orderRepository = $orderRepository;
	}

	/**
	 * @param StoreOrderPrepareCheckoutCommandInterface $command
	 * @return array
	 * @throws StoreOrderException
	 */
	public function execute(StoreOrderPrepareCheckoutCommandInterface $command)
	{
		/** @var StoreOrder $order */
		$order = $this->orderRepository->findById($command->getOrder());

		if (!$order) {
			throw new StoreOrderException('Cant find order with id: ' . $command->getOrder());
		}

		if ($order->getState() != StoreOrder::STATE_NEW) {
			throw new StoreOrderException('Order was closed or already payed: ' . $order->getId());
		}

		$result = [
			'price' => $order->getItemsPriceTotal(),
			'userEmail' => $order->getUser()->getEmail(),
			'apiKey' => PaymentGatewayStripe::getPublicKey()
		];

		return $result;
	}
}