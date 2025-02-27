<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Store\Order;

use App\AddHash\AdminPanel\Domain\Payment\Payment;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderException;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderRepositoryInterface;
use App\AddHash\AdminPanel\Domain\User\Services\UserGetAuthenticationServiceInterface;
use App\AddHash\AdminPanel\Domain\Payment\Services\MakePaymentForOrderServiceInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Command\StoreOrderCheckoutCommandInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Services\StoreOrderCheckoutServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Notification\SendUserNotificationServiceInterface;

class StoreOrderCheckoutService implements StoreOrderCheckoutServiceInterface
{
	private $orderRepository;

	private $payment;

	private $authenticationService;

    private $notificationService;

	public function __construct(
		StoreOrderRepositoryInterface $orderRepository,
		MakePaymentForOrderServiceInterface $makePaymentForOrderService,
        UserGetAuthenticationServiceInterface $authenticationService,
        SendUserNotificationServiceInterface $notificationService
	)
	{
		$this->orderRepository = $orderRepository;
		$this->payment = $makePaymentForOrderService;
		$this->authenticationService = $authenticationService;
        $this->notificationService = $notificationService;
	}

	/**
	 * @param StoreOrderCheckoutCommandInterface $commandOrder
	 * @return StoreOrder
	 * @throws StoreOrderException
	 */
	public function execute(StoreOrderCheckoutCommandInterface $commandOrder): StoreOrder
	{
        $user = $this->authenticationService->execute();

		/** @var StoreOrder $order */
		$order = $this->orderRepository->findNewByUserId($user->getId());

		if (!$order) {
			throw new StoreOrderException('Cant find order by user');
		}

		if ($order->getState() != StoreOrder::STATE_NEW) {
			throw new StoreOrderException('Order was closed or already payed: ' . $order->getId());
		}

		if (!$order->checkReservedMiners()) {
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

		$title = 'System notification';
		$message = 'Payment by order #' . $order->getId() . ' was successful';

        $this->notificationService->execute($title, $message, $user);

		return $order;
	}
}