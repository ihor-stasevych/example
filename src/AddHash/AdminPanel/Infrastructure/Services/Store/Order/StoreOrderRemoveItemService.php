<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Store\Order;

use App\AddHash\AdminPanel\Domain\Miners\Repository\MinerRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Command\StoreOrderAddProductCommandInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Item\StoreOrderItem;
use App\AddHash\AdminPanel\Domain\Store\Order\Item\StoreOrderItemRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Services\StoreOrderAddProductServiceInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Services\StoreOrderRemoveItemServiceInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderException;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Store\Product\StoreProduct;
use App\AddHash\AdminPanel\Domain\User\Services\UserGetAuthenticationServiceInterface;
use App\AddHash\AdminPanel\Infrastructure\Repository\Store\Product\StoreProductRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class StoreOrderRemoveItemService implements StoreOrderRemoveItemServiceInterface
{
	private $storeOrderRepository;
	private $productRepository;
	private $storeOrderItemRepository;
	private $minerRepository;
	private $authenticationService;

	public function __construct(
		StoreOrderRepositoryInterface $storeOrderRepository,
		StoreProductRepository $productRepository,
		StoreOrderItemRepositoryInterface $orderItemRepository,
		MinerRepositoryInterface $minerRepository,
        UserGetAuthenticationServiceInterface $authenticationService
	)
	{
		$this->storeOrderRepository = $storeOrderRepository;
		$this->productRepository = $productRepository;
		$this->storeOrderItemRepository = $orderItemRepository;
		$this->minerRepository = $minerRepository;
        $this->authenticationService = $authenticationService;
	}

	/**
	 * @param $id
	 * @return bool
	 * @throws StoreOrderException
	 */
	public function execute($id)
	{
        $user = $this->authenticationService->execute();

		$order = $this->storeOrderRepository->findNewByUserId($user->getId());

		if (!$order) {
			throw new StoreOrderException('Order not found');
		}

		/** @var StoreOrderItem $item */
		$item = $this->storeOrderItemRepository->findById($id);

		if (!$item) {
			throw new StoreOrderException('Order item not found');
		}

		$order->removeItem($item);
		$product = $item->getProduct();
		$this->storeOrderItemRepository->delete($item);

		$miner = $product->unReserveMiner();
		$this->minerRepository->save($miner);

		$order->calculateItems();

		if ($order->getItems()->count() == 0) {
			$order->closeOrder();
		}

		$this->storeOrderRepository->save($order);

		return true;
	}
}