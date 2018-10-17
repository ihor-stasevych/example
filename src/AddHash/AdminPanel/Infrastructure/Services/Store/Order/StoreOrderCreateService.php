<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Store\Order;

use App\AddHash\AdminPanel\Domain\User\User;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use App\AddHash\AdminPanel\Domain\Store\Product\StoreProduct;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderException;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Store\Product\StoreProductRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Miners\Repository\MinerStockRepositoryInterface;
use App\AddHash\AdminPanel\Domain\User\Services\UserGetAuthenticationServiceInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Command\StoreOrderCreateCommandInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Services\StoreOrderCreateServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Notification\SendUserNotificationServiceInterface;

class StoreOrderCreateService implements StoreOrderCreateServiceInterface
{
	private $storeProductRepository;

	private $storeOrderRepository;

	private $minerStockRepository;

	private $authenticationService;

	private $notificationService;

	public function __construct(
		StoreProductRepositoryInterface $productRepository,
		StoreOrderRepositoryInterface $orderRepository,
        UserGetAuthenticationServiceInterface $authenticationService,
        MinerStockRepositoryInterface $minerStockRepository,
		SendUserNotificationServiceInterface $notificationService
	)
	{
		$this->storeProductRepository = $productRepository;
		$this->storeOrderRepository = $orderRepository;
		$this->authenticationService = $authenticationService;
		$this->minerStockRepository = $minerStockRepository;
		$this->notificationService = $notificationService;
	}

	/**
	 * @param StoreOrderCreateCommandInterface $command
	 * @return StoreOrder
	 * @throws StoreOrderException
	 */
	public function execute(StoreOrderCreateCommandInterface $command): StoreOrder
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

		$this->notificationService->execute('System notification', 'Order was created #' . $order->getId());

		return $order;
	}

	private function closeOldOrderAndUnReserveMiners(User $user)
    {
        $order = $this->storeOrderRepository->findNewByUserId($user->getId());

        if (null !== $order) {
            $order->closeOrder();
            $items = $order->getItems();

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

            $this->storeOrderRepository->save($order);
        }
    }
}