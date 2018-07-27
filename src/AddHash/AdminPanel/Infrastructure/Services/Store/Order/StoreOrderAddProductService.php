<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Store\Order;

use App\AddHash\AdminPanel\Domain\Miners\Repository\MinerRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Command\StoreOrderAddProductCommandInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Item\StoreOrderItemRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Services\StoreOrderAddProductServiceInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderException;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Store\Product\StoreProduct;
use App\AddHash\AdminPanel\Infrastructure\Repository\Store\Product\StoreProductRepository;

class StoreOrderAddProductService implements StoreOrderAddProductServiceInterface
{
	private $storeOrderRepository;
	private $productRepository;
	private $storeOrderItemRepository;
	private $minerRepository;

	public function __construct(
		StoreOrderRepositoryInterface $storeOrderRepository,
		StoreProductRepository $productRepository,
		StoreOrderItemRepositoryInterface $orderItemRepository,
		MinerRepositoryInterface $minerRepository
	)
	{
		$this->storeOrderRepository = $storeOrderRepository;
		$this->productRepository = $productRepository;
		$this->storeOrderItemRepository = $orderItemRepository;
		$this->minerRepository = $minerRepository;
	}

	/**
	 * @param StoreOrderAddProductCommandInterface $command
	 * @return bool
	 * @throws StoreOrderException
	 */
	public function execute(StoreOrderAddProductCommandInterface $command)
	{
		/** @var StoreOrder $order */
		$order = $this->storeOrderRepository->findById($command->getOrder());

		if (!$order) {
			throw new StoreOrderException('Order not found');
		}

		/** @var StoreProduct $product */
		$product = $this->productRepository->findById($command->getProduct());

		if (!$product) {
			throw new StoreOrderException('Product not found');
		}

		if (!$item = $order->addProductItem($product)) {
			throw new StoreOrderException('Cant add ' . $product->getTitle() . ' to cart. No available miners.');
		}

		$this->storeOrderItemRepository->save($item);
		$miner = $product->reserveMiner();
		$this->minerRepository->save($miner);

		$order->calculateItems();
		$this->storeOrderRepository->save($order);

		return true;
	}
}