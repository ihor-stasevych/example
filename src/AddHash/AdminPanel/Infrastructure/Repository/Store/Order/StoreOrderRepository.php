<?php

namespace App\AddHash\AdminPanel\Infrastructure\Repository\Store\Order;

use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\NonUniqueResultException;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;
use App\AddHash\AdminPanel\Domain\User\Order\History\ListParam\Sort;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderRepositoryInterface;

class StoreOrderRepository extends AbstractRepository implements StoreOrderRepositoryInterface
{
	/**
	 * @param $id
	 * @return null|object|StoreOrder
	 */
	public function findById(int $id)
	{
		return $this->entityRepository->find($id);
	}

	/**
	 * @param $userId
	 * @return mixed
	 * @throws NonUniqueResultException
	 */
	public function findNewByUserId(int $userId): ?StoreOrder
	{
		return $this->getNewByUserId($userId)->getOneOrNullResult();
	}

	public function findAllNewByUserId(int $userId): array
	{
		return $this->getNewByUserId($userId)->getResult();
	}

    /**
     * @param int $id
     * @return mixed
     * @throws NonUniqueResultException
     */
	public function findNewById(int $id): ?StoreOrder
    {
        return $this->entityRepository->createQueryBuilder('e')
            ->select('e')
            ->where('e.id = :id')
            ->andWhere('e.state = :stateNew')
            ->setParameter('id', $id)
            ->setParameter('stateNew', StoreOrder::STATE_NEW)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param \DateTime $updatedAt
     * @return mixed
     */
	public function getNewByTime(\DateTime $updatedAt): array
    {
        return $this->entityRepository->createQueryBuilder('e')
            ->select('e')
            ->leftJoin('e.payment', 'p')
            ->leftJoin('p.paymentMethod', 'pm')
            ->leftJoin('e.items', 'i')
            ->leftJoin('i.product', 'pr')
            ->leftJoin('pr.miner', 'm')
            ->leftJoin('m.stock', 'ms')
            ->where('e.state = :stateNew')
            ->andWhere('e.updatedAt < :updatedAt')
            ->setParameter('stateNew', StoreOrder::STATE_NEW)
            ->setParameter('updatedAt', $updatedAt)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $userId
     * @param Sort $sort
     * @param null $state
     * @return array
     */
    public function getOrdersByUserId(int $userId, Sort $sort, $state = null): array
    {
        $order = $this->entityRepository->createQueryBuilder('o')
            ->select('o')
            ->where('o.user = :userId');

        if (null !== $state) {
            $order = $order->andWhere('o.state  = :state');
        }

        $order = $order->orderBy('o.' . $sort->getSort(), $sort->getOrder())
            ->setParameter('userId', $userId);

        if (null !== $state) {
            $order = $order->setParameter('state', $state);
        }

        $order = $order->getQuery()
            ->getResult();

        return $order;
    }

    /**
     * @param int $id
     * @param int $userId
     * @return StoreOrder|null
     * @throws NonUniqueResultException
     */
    public function getOrderByIdAndUserId(int $id, int $userId): ?StoreOrder
    {
        return $this->entityRepository->createQueryBuilder('o')
            ->select('o')
            ->where('o.id = :id')
            ->andWhere('o.user = :userId')
            ->setParameter('id', $id)
            ->setParameter('userId', $userId)
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
		$this->entityManager->flush();
	}


	/**
	 * @param StoreOrder $order
	 * @throws ORMException
	 * @throws OptimisticLockException
	 */
	public function remove(StoreOrder $order)
	{

		$this->entityManager->remove($order);
		$this->entityManager->flush();

		/**
		$isDeleted = $this->entityRepository->createQueryBuilder('order')
			->delete()
			->where('order.id  = :orderId')->setParameter('orderId', $order->getId())
			->getQuery()->execute();

		return $isDeleted;
		 * */
	}

	protected function getNewByUserId($userId)
	{
		return $this->entityRepository->createQueryBuilder('e')
			->select('e')
			->where('e.user = :id')
			->andWhere('e.state = :stateNew')
			->setParameter('id', $userId)
			->setParameter('stateNew', StoreOrder::STATE_NEW)
			->getQuery();
	}

	/**
	 * @return string
	 */
	protected function getEntityName()
	{
		return StoreOrder::class;
	}
}