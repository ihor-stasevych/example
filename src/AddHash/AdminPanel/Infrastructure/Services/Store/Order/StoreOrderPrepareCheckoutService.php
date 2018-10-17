<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Store\Order;

use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderException;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderRepositoryInterface;
use App\AddHash\AdminPanel\Domain\AdapterOpenHost\AuthenticationAdapterInterface;
use App\AddHash\AdminPanel\Domain\User\Services\UserGetAuthenticationServiceInterface;
use App\AddHash\AdminPanel\Infrastructure\Payment\Gateway\Stripe\PaymentGatewayStripe;
use App\AddHash\AdminPanel\Domain\Store\Order\Services\StoreOrderPrepareCheckoutServiceInterface;

class StoreOrderPrepareCheckoutService implements StoreOrderPrepareCheckoutServiceInterface
{
	private $orderRepository;

	private $authenticationService;

	private $authenticationAdapter;

	public function __construct(
		StoreOrderRepositoryInterface $orderRepository,
        UserGetAuthenticationServiceInterface $authenticationService,
        AuthenticationAdapterInterface $authenticationAdapter
	)
	{
		$this->orderRepository = $orderRepository;
		$this->authenticationService = $authenticationService;
		$this->authenticationAdapter = $authenticationAdapter;
	}

	/**
	 * @return array
	 * @throws StoreOrderException
	 */
	public function execute(): array
	{
        $user = $this->authenticationService->execute();

		/** @var StoreOrder $order */
		$order = $this->orderRepository->findNewByUserId($user->getId());

		if (null === $order) {
			throw new StoreOrderException('Cant find order');
		}

        $credentials = $this->authenticationAdapter->getCredentials();

		$result = [
			'price'     => $order->getItemsPriceTotal(),
			'userEmail' => $credentials['email'],
			'apiKey'    => PaymentGatewayStripe::getPublicKey()
		];

		return $result;
	}
}