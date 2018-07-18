<?php

namespace App\AddHash\AdminPanel\Infrastructure\Repository\Store\Order;

use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use App\AddHash\AdminPanel\Domain\Store\Order\OrderRepositoryInterface;
use Doctrine\ORM\EntityManager;

class StoreOrderRepository implements OrderRepositoryInterface
{
	protected $entityManager;
	protected $orderRepository;

	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
		$this->orderRepository = $this->entityManager->getRepository(StoreOrder::class);
	}

	public function findById($id)
	{
		// TODO: Implement findById() method.
	}

	/**
	 * @param $userId
	 * @return mixed
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function findNewByUserId($userId)
	{
		$order = $this->orderRepository->createQueryBuilder('e')
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
}