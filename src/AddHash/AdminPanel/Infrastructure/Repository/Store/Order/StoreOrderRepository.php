<?php

namespace App\AddHash\AdminPanel\Infrastructure\Repository\Store\Order;

use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\NonUniqueResultException;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderRepositoryInterface;

class StoreOrderRepository extends AbstractRepository implements StoreOrderRepositoryInterface
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
	 * @throws NonUniqueResultException
	 */
	public function findNewByUserId($userId)
	{
		return $this->entityRepository->createQueryBuilder('e')
			->select('e')
			->where('e.user = :id')
			->andWhere('e.state = :stateNew')
			->setParameter('id', $userId)
			->setParameter('stateNew', StoreOrder::STATE_NEW)
			->getQuery()
			->getOneOrNullResult();
	}

    /**
     * @param \DateTime $updatedAt
     * @return mixed
     */
	public function getNewByTime(\DateTime $updatedAt)
    {
        return $this->entityRepository->createQueryBuilder('e')
            ->select('e')
            ->where('e.state = :stateNew')
            ->andWhere('e.updatedAt < :updatedAt')
            ->setParameter('stateNew', StoreOrder::STATE_NEW)
            ->setParameter('updatedAt', $updatedAt)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $userId
     * @return array
     */
    public function getOrdersPaidByUserId(int $userId): array
    {
        return $this->entityRepository->createQueryBuilder('o')
            ->select('o')
            ->where('o.user = :userId')
            ->andWhere('o.state = :state')
            ->setParameter('userId', $userId)
            ->setParameter('state', StoreOrder::STATE_PAYED)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $id
     * @param int $userId
     * @return StoreOrder|null
     * @throws NonUniqueResultException
     */
    public function getOrderPaidByIdAndUserId(int $id, int $userId): ?StoreOrder
    {
        return $this->entityRepository->createQueryBuilder('o')
            ->select('o')
            ->where('o.id = :id')
            ->andWhere('o.user = :userId')
            ->andWhere('o.state = :state')
            ->setParameter('id', $id)
            ->setParameter('userId', $userId)
            ->setParameter('state', StoreOrder::STATE_PAYED)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param StoreOrder $order
     * @throws ORMException
     * @throws OptimisticLockException
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