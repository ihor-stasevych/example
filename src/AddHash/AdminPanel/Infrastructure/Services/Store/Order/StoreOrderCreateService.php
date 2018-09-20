<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Store\Order;

use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use App\AddHash\AdminPanel\Domain\Store\Product\StoreProduct;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderException;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Store\Product\StoreProductRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Miners\Repository\MinerStockRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Item\StoreOrderItemRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Command\StoreOrderCreateCommandInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Services\StoreOrderCreateServiceInterface;

class StoreOrderCreateService implements StoreOrderCreateServiceInterface
{
	private $storeProductRepository;

	private $storeOrderRepository;

	private $storeOrderItemRepository;

	private $minerStockRepository;

	private $tokenStorage;

	public function __construct(
		StoreProductRepositoryInterface $productRepository,
		StoreOrderRepositoryInterface $orderRepository,
		StoreOrderItemRepositoryInterface $orderItemRepository,
		TokenStorageInterface $tokenStorage,
        MinerStockRepositoryInterface $minerStockRepository
	)
	{
		$this->storeProductRepository = $productRepository;
		$this->storeOrderRepository = $orderRepository;
		$this->storeOrderItemRepository = $orderItemRepository;
		$this->tokenStorage = $tokenStorage;
		$this->minerStockRepository = $minerStockRepository;
	}

	/**
	 * @param StoreOrderCreateCommandInterface $command
	 * @return StoreOrder
	 * @throws StoreOrderException
	 */
	public function execute(StoreOrderCreateCommandInterface $command): StoreOrder
	{
		/** @var StoreOrder $order */
		$order = $this->storeOrderRepository->findNewByUserId($command->getUser()->getId());

		if (empty($order)) {
			$order = new StoreOrder($command->getUser());
		}

		$order->removeItems();

		foreach ($command->getProducts() as $productId => $quantity) {
			/** @var StoreProduct $product */
			$product = $this->storeProductRepository->findById($productId);

			if (!$product) {
				throw new StoreOrderException('No available product: ' . $productId);
			}

			if (!$item = $order->addProductItem($product, $quantity)) {
				throw new StoreOrderException('Cant add ' . $product->getTitle() . ' to cart. No available miners.');
			}

            for ($i = 0; $i < $quantity; $i++) {
                $miner = $product->reserveMiner();

                if (!$miner) {
                    break;
                }

                $this->minerStockRepository->save($miner);
            }

			#$this->storeOrderItemRepository->save($item);
		}

		$order->calculateItems();
		$this->storeOrderRepository->save($order);

		return $order;
	}
}