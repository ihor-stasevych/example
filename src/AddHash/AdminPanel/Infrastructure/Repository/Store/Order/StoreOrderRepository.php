<?php

namespace App\AddHash\AdminPanel\Infrastructure\Repository\Store\Order;

use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use App\AddHash\AdminPanel\Domain\Store\Order\OrderRepositoryInterface;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;

class StoreOrderRepository extends AbstractRepository implements OrderRepositoryInterface
{

	/**
	 * @param $id
	 * @return null|object|StoreOrder
	 */
	public function findById($id)
	{
		return $this->entityRepository->find($id);
	}

	/**
	 * @param $userId
	 * @return mixed
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function findNewByUserId($userId)
	{
		$order = $this->entityRepository->createQueryBuilder('e')
			->select('e')
			->where('e.user = :id')
			->andWhere('e.state = :stateNew')
			->setParameter('id', $userId)
			->setParameter('stateNew', StoreOrder::STATE_NEW)
			->getQuery()
			->getOneOrNullResult();

		return $order;
	}

	/**
	 * @param StoreOrder $order
	 * @throws \Doctrine\ORM\ORMException
	 * @throws \Doctrine\ORM\OptimisticLockException
	 */
	public function save(StoreOrder $order)
	{
		$this->entityManager->persist($order);
		$this->entityManager->flush($order);
	}

	/**
	 * @return string
	 */
	protected function getEntityName()
	{
		return StoreOrder::class;
	}
}