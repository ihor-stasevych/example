<?php

namespace App\AddHash\AdminPanel\Infrastructure\Repository\User\Order\Miner;

use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\NonUniqueResultException;
use App\AddHash\AdminPanel\Domain\User\User;
use App\AddHash\AdminPanel\Domain\User\Order\UserOrderMiner;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;
use App\AddHash\AdminPanel\Domain\User\Order\UserOrderMinerRepositoryInterface;

class UserOrderMinerRepository extends AbstractRepository implements UserOrderMinerRepositoryInterface
{
    public function getByEndPeriod(\DateTime $endPeriod): array
    {
        return $this->entityManager->getRepository($this->getEntityName())
            ->createQueryBuilder('uom')
            ->select('uom')
            ->join('uom.minerStock', 'ms')
            ->join('uom.user', 'u')
            ->where('uom.endPeriod < :endPeriod')
            ->setParameter('endPeriod', $endPeriod)
            ->getQuery()
            ->getResult();
    }

    public function getByBetweenEndPeriod(\DateTime $start, \DateTime $end): array
    {
        return $this->entityManager->getRepository($this->getEntityName())
            ->createQueryBuilder('uom')
            ->select('uom')
            ->join('uom.minerStock', 'ms')
            ->join('uom.user', 'u')
            ->where('uom.endPeriod BETWEEN :start AND :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param User $user
     * @param int $minerStockId
     * @return UserOrderMiner|null
     * @throws NonUniqueResultException
     */
    public function getByUserAndMinerStockId(User $user, int $minerStockId): ?UserOrderMiner
    {
        return $this->entityManager->getRepository($this->getEntityName())
            ->createQueryBuilder('uom')
            ->select('uom', 'ms')
            ->join('uom.minerStock', 'ms')
            ->join('ms.miner', 'm')
            ->where('uom.user = :user')
            ->andWhere('ms.id = :minerStockId')
            ->setParameter('user', $user)
            ->setParameter('minerStockId', $minerStockId)
            ->getQuery()
            ->getOneOrNullResult();
    }

	/**
	 * @param UserOrderMiner $userOrderMiner
	 * @throws ORMException
	 * @throws OptimisticLockException
	 */
	public function save(UserOrderMiner $userOrderMiner)
	{
		$this->entityManager->persist($userOrderMiner);
		$this->entityManager->flush($userOrderMiner);
	}

    /**
     * @param UserOrderMiner $userOrderMiner
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(UserOrderMiner $userOrderMiner)
    {
        $this->entityManager->remove($userOrderMiner);
        $this->entityManager->flush();
    }

	/**
	 * @return string
	 */
	protected function getEntityName()
	{
		return UserOrderMiner::class;
	}
}