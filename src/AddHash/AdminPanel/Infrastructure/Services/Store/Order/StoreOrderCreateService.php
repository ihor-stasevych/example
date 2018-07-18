<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Store\Order;


use App\AddHash\AdminPanel\Domain\Store\Order\Command\StoreOrderCreateCommandInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Item\StoreOrderItem;
use App\AddHash\AdminPanel\Domain\Store\Order\Item\StoreOrderItemRepositoryInterface;

use App\AddHash\AdminPanel\Domain\Store\Order\OrderRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use App\AddHash\AdminPanel\Domain\Store\Product\StoreProduct;
use App\AddHash\AdminPanel\Domain\Store\Product\StoreProductRepositoryInterface;

use App\AddHash\AdminPanel\Domain\Store\Order\Services\StoreOrderCreateServiceInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class StoreOrderCreateService implements StoreOrderCreateServiceInterface
{
	private $storeProductRepository;
	private $storeOrderRepository;
	private $storeOrderItemRepository;
	private $tokenStorage;

	public function __construct(
		StoreProductRepositoryInterface $productRepository,
		OrderRepositoryInterface $orderRepository,
		StoreOrderItemRepositoryInterface $orderItemRepository,
		TokenStorageInterface $tokenStorage
	)
	{
		$this->storeProductRepository = $productRepository;
		$this->storeOrderRepository = $orderRepository;
		$this->storeOrderItemRepository = $orderItemRepository;
		$this->tokenStorage = $tokenStorage;
	}

	public function execute(StoreOrderCreateCommandInterface $command)
	{
		$order = $this->storeOrderRepository->findNewByUserId($command->getUser()->getId());

		if (empty($order)) {
			$order = new StoreOrder($command->getUser());
		}

		$products = $this->storeProductRepository->findByIds($command->getProducts());

		/** @var StoreProduct $product */
		foreach ($products as $product) {
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
		}

		$order->calculateItems();

		$this->storeOrderRepository->save($order);

		return $order;
	}
}