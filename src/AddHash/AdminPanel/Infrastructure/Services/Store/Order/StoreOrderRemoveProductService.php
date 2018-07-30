<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Store\Order;

use App\AddHash\AdminPanel\Domain\Miners\Repository\MinerRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Command\StoreOrderAddProductCommandInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Item\StoreOrderItemRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Services\StoreOrderAddProductServiceInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Services\StoreOrderRemoveProductServiceInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderException;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Store\Product\StoreProduct;
use App\AddHash\AdminPanel\Infrastructure\Repository\Store\Product\StoreProductRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class StoreOrderRemoveProductService implements StoreOrderRemoveProductServiceInterface
{
	private $storeOrderRepository;
	private $productRepository;
	private $storeOrderItemRepository;
	private $minerRepository;
	private $tokenStorage;

	public function __construct(
		StoreOrderRepositoryInterface $storeOrderRepository,
		StoreProductRepository $productRepository,
		StoreOrderItemRepositoryInterface $orderItemRepository,
		MinerRepositoryInterface $minerRepository,
		TokenStorageInterface $tokenStorage
	)
	{
		$this->storeOrderRepository = $storeOrderRepository;
		$this->productRepository = $productRepository;
		$this->storeOrderItemRepository = $orderItemRepository;
		$this->minerRepository = $minerRepository;
		$this->tokenStorage = $tokenStorage;
	}

	/**
	 * @param $id
	 * @return bool
	 * @throws StoreOrderException
	 */
	public function execute($id)
	{
		/** @var StoreOrder $order */
		$order = $this->storeOrderRepository->findNewByUserId($this->tokenStorage->getToken()->getUser());

		if (!$order) {
			throw new StoreOrderException('Order not found');
		}

		/** @var StoreProduct $product */
		$product = $this->productRepository->findById($id);

		if (!$product) {
			throw new StoreOrderException('Product not found');
		}

		if ($item = $order->removeItemByProduct($product)) {
			$this->storeOrderItemRepository->delete($item);
		}

		$miner = $product->unReserveMiner();
		$this->minerRepository->save($miner);

		$order->calculateItems();
		$this->storeOrderRepository->save($order);

		return true;
	}
}