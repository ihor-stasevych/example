<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Store\Order;


use App\AddHash\AdminPanel\Domain\Miners\Repository\MinerRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Command\StoreOrderCreateCommandInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Item\StoreOrderItem;
use App\AddHash\AdminPanel\Domain\Store\Order\Item\StoreOrderItemRepositoryInterface;

use App\AddHash\AdminPanel\Domain\Store\Order\OrderRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderException;
use App\AddHash\AdminPanel\Domain\Store\Product\StoreProduct;
use App\AddHash\AdminPanel\Domain\Store\Product\StoreProductRepositoryInterface;

use App\AddHash\AdminPanel\Domain\Store\Order\Services\StoreOrderCreateServiceInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class StoreOrderCreateService implements StoreOrderCreateServiceInterface
{
	private $storeProductRepository;
	private $storeOrderRepository;
	private $storeOrderItemRepository;
	private $minerRepository;
	private $tokenStorage;

	public function __construct(
		StoreProductRepositoryInterface $productRepository,
		OrderRepositoryInterface $orderRepository,
		StoreOrderItemRepositoryInterface $orderItemRepository,
		TokenStorageInterface $tokenStorage,
		MinerRepositoryInterface $minerRepository
	)
	{
		$this->storeProductRepository = $productRepository;
		$this->storeOrderRepository = $orderRepository;
		$this->storeOrderItemRepository = $orderItemRepository;
		$this->tokenStorage = $tokenStorage;
		$this->minerRepository = $minerRepository;
	}

	/**
	 * @param StoreOrderCreateCommandInterface $command
	 * @return StoreOrder
	 * @throws StoreOrderException
	 */
	public function execute(StoreOrderCreateCommandInterface $command)
	{
		$order = $this->storeOrderRepository->findNewByUserId($command->getUser()->getId());

		if (empty($order)) {
			$order = new StoreOrder($command->getUser());
		}

		$products = $this->storeProductRepository->findByIds($command->getProducts());

		/** @var StoreProduct $product */
		foreach ($products as $product) {

			if ($product->getAvailableMinersQuantity() == 0) {
				throw new StoreOrderException('Cant add ' . $product->getTitle() . ' to cart. No available miners.');
			}

			if (!$order->productContains($product)) {
				$item = new StoreOrderItem($order, $product);
				$order->addItem($item);
			} else {
				$key = $order->indexOfProduct($product);
				/** @var StoreOrderItem $item */
				$item = $order->getItems()->get($key);
				$item->addQuantity();
				$item->calculateTotalPrice();
				$this->storeOrderItemRepository->save($item);
			}

			$miner = $product->reserveMiner();
			$this->minerRepository->save($miner);
		}

		$order->calculateItems();
		$this->storeOrderRepository->save($order);

		return $order;
	}
}