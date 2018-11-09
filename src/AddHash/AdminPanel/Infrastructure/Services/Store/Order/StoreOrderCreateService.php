<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Store\Order;

use App\AddHash\AdminPanel\Domain\Store\Order\Item\StoreOrderItem;
use App\AddHash\AdminPanel\Domain\User\User;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use App\AddHash\AdminPanel\Domain\Store\Product\StoreProduct;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderException;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderRepositoryInterface;
use App\AddHash\AdminPanel\Infrastructure\AdapterOpenHost\AuthenticationAdapter;
use App\AddHash\AdminPanel\Domain\Store\Product\StoreProductRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Miners\Repository\MinerStockRepositoryInterface;
use App\AddHash\AdminPanel\Infrastructure\Payment\Gateway\Stripe\PaymentGatewayStripe;
use App\AddHash\AdminPanel\Domain\User\Services\UserGetAuthenticationServiceInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Command\StoreOrderCreateCommandInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Services\StoreOrderCreateServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Notification\SendUserNotificationServiceInterface;

final class StoreOrderCreateService implements StoreOrderCreateServiceInterface
{
	private $storeProductRepository;

	private $storeOrderRepository;

	private $minerStockRepository;

	private $authenticationService;

	private $notificationService;

	private $authenticationAdapter;

	public function __construct(
		StoreProductRepositoryInterface $productRepository,
		StoreOrderRepositoryInterface $orderRepository,
        UserGetAuthenticationServiceInterface $authenticationService,
        MinerStockRepositoryInterface $minerStockRepository,
		SendUserNotificationServiceInterface $notificationService,
        AuthenticationAdapter $authenticationAdapter
	)
	{
		$this->storeProductRepository = $productRepository;
		$this->storeOrderRepository = $orderRepository;
		$this->authenticationService = $authenticationService;
		$this->minerStockRepository = $minerStockRepository;
		$this->notificationService = $notificationService;
		$this->authenticationAdapter = $authenticationAdapter;
	}

    /**
     * @param StoreOrderCreateCommandInterface $command
     * @return array
     * @throws StoreOrderException
     */
	public function execute(StoreOrderCreateCommandInterface $command): array
	{
        $user = $this->authenticationService->execute();

        $this->closeOldOrderAndUnReserveMiners($user);

        $order = new StoreOrder($user);

		foreach ($command->getProducts() as $productId => $quantity) {
			/** @var StoreProduct $product */
			$product = $this->storeProductRepository->findById($productId);

			if (!$product) {
				throw new StoreOrderException('No available product: ' . $productId);
			}

            if (false === $order->addProductItem($product, $quantity)) {
                throw new StoreOrderException('Cant add ' . $product->getTitle() . ' to cart. No available miners.');
            }

            for ($i = 0; $i < $quantity; $i++) {
                $miner = $product->reserveMiner();

                if (!$miner) {
                    break;
                }

                $this->minerStockRepository->save($miner);
            }
		}

		$order->calculateItems();
		$this->storeOrderRepository->save($order);

		$title = 'System notification';
		$message = 'Order was created #' . $order->getId();

		$this->notificationService->execute($title, $message, $user);

        $credentials = $this->authenticationAdapter->getCredentials();

		return [
            'id'        => $order->getId(),
            'price'     => $order->getItemsPriceTotal(),
            'userEmail' => $credentials['email'],
            'apiKey'    => PaymentGatewayStripe::getPublicKey()
        ];
	}

	private function closeOldOrderAndUnReserveMiners(User $user)
    {
       $orders = $this->storeOrderRepository->findAllNewByUserId($user->getId());

	   if (empty($orders)) {
	    	return true;
	   }

	   /** @var StoreOrder $order */
	    foreach ($orders as $order) {
		   $order->closeOrder();
		   $items = $order->getItems();

		   /** @var StoreOrderItem $item */
		   foreach ($items as $item) {
			   $quantity = $item->getQuantity();

			   for ($i = 0; $i < $quantity; $i++) {
				   $minerStock = $item->getProduct()->unReserveMiner();

				   if (!$minerStock) {
					   break;
				   }

				   $this->minerStockRepository->save($minerStock);
			   }
		   }

		   $this->storeOrderRepository->remove($order);
	   }

       return true;
    }
}