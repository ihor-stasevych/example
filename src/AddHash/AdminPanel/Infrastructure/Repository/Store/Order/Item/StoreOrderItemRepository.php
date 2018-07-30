<?php

namespace App\AddHash\AdminPanel\Infrastructure\Repository\Store\Order\Item;


use App\AddHash\AdminPanel\Domain\Store\Order\Item\StoreOrderItem;
use App\AddHash\AdminPanel\Domain\Store\Order\Item\StoreOrderItemRepositoryInterface;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;
use Doctrine\ORM\EntityManager;

class StoreOrderItemRepository extends AbstractRepository implements StoreOrderItemRepositoryInterface
{

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

	public function findById($id)
	{
		return $this->entityRepository->find($id);
	}

	/**
	 * @return string
	 */
	protected function getEntityName()
	{
		return StoreOrderItem::class;
	}
}