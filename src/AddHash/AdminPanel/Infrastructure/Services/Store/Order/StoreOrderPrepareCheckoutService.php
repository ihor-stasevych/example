<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Store\Order;

use App\AddHash\AdminPanel\Domain\Store\Order\Command\StoreOrderPrepareCheckoutCommandInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Services\StoreOrderPrepareCheckoutServiceInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderException;
use App\AddHash\AdminPanel\Infrastructure\Payment\Gateway\Stripe\PaymentGatewayStripe;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class StoreOrderPrepareCheckoutService implements StoreOrderPrepareCheckoutServiceInterface
{
	private $orderRepository;
	private $tokenStorage;

	public function __construct(
		StoreOrderRepositoryInterface $orderRepository,
		TokenStorageInterface $tokenStorage
	)
	{
		$this->orderRepository = $orderRepository;
		$this->tokenStorage = $tokenStorage;
	}

	/**
	 * @return array
	 * @throws StoreOrderException
	 */
	public function execute()
	{
		$token = $this->tokenStorage->getToken();

		if (empty($token)) {
			throw new StoreOrderException('Unauthorized');
		}

		$user = $token->getUser();

		/** @var StoreOrder $order */
		$order = $this->orderRepository->findNewByUserId($user->getId());

		if (!$order) {
			throw new StoreOrderException('Cant find order');
		}

		$result = [
			'price' => $order->getItemsPriceTotal(),
			'userEmail' => $order->getUser()->getEmail(),
			'apiKey' => PaymentGatewayStripe::getPublicKey()
		];

		return $result;
	}
}