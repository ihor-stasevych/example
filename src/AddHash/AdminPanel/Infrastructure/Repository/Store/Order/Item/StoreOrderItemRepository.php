<?php

namespace App\AddHash\AdminPanel\Infrastructure\Repository\Store\Order\Item;


use App\AddHash\AdminPanel\Domain\Store\Order\Item\StoreOrderItem;
use App\AddHash\AdminPanel\Domain\Store\Order\Item\StoreOrderItemRepositoryInterface;
use Doctrine\ORM\EntityManager;

class StoreOrderItemRepository implements StoreOrderItemRepositoryInterface
{
	private $entityManager;

	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	/**
	 * @param StoreOrderItem $orderItem
	 * @throws \Doctrine\ORM\ORMException
	 * @throws \Doctrine\ORM\OptimisticLockException
	 */
	public function save(StoreOrderItem $orderItem)
	{
		$this->entityManager->persist($orderItem);
		$this->entityManager->flush($orderItem);
	}

	/**
	 * @param StoreOrderItem $orderItem
	 * @throws \Doctrine\ORM\ORMException
	 * @throws \Doctrine\ORM\OptimisticLockException
	 */
	public function delete(StoreOrderItem $orderItem)
	{
		$this->entityManager->remove($orderItem);
		$this->entityManager->flush($orderItem);
	}
}