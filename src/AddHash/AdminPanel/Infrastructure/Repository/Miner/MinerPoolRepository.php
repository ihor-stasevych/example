<?php

namespace App\AddHash\AdminPanel\Infrastructure\Repository\Miner;

use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use App\AddHash\AdminPanel\Domain\Miners\MinerPool;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;
use App\AddHash\AdminPanel\Domain\Miners\Repository\MinerPoolRepositoryInterface;

class MinerPoolRepository extends AbstractRepository implements MinerPoolRepositoryInterface
{
    /**
     * @param MinerPool $minerPool
     * @throws ORMException
     * @throws OptimisticLockException
     */
	public function save(MinerPool $minerPool)
	{
		$this->entityManager->persist($minerPool);
		$this->entityManager->flush($minerPool);
	}

    /**
     * @param int $minerStockId
     * @return mixed
     */
	public function deleteByMinerStock(int $minerStockId): int
    {
        return $this->entityManager->getRepository($this->getEntityName())
            ->createQueryBuilder('mp')
            ->delete()
            ->where('mp.minerStock = :minerStockId')
            ->setParameter('minerStockId', $minerStockId)
            ->getQuery()
            ->execute();
    }

	/**
	 * @return string
	 */
	protected function getEntityName()
	{
		return MinerPool::class;
	}
}