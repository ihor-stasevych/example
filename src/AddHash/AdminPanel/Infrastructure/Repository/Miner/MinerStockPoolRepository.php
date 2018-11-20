<?php

namespace App\AddHash\AdminPanel\Infrastructure\Repository\Miner;

use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use App\AddHash\AdminPanel\Domain\Miners\MinerStock;
use App\AddHash\AdminPanel\Domain\Miners\MinerStockPool;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;
use App\AddHash\AdminPanel\Domain\Miners\Repository\MinerStockPoolRepositoryInterface;

class MinerStockPoolRepository extends AbstractRepository implements MinerStockPoolRepositoryInterface
{
    /**
     * @param array $minerStockPools
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function saveAll(array $minerStockPools): void
    {
        if (count($minerStockPools) > 0) {
            foreach ($minerStockPools as $minerStockPool) {
                $this->entityManager->persist($minerStockPool);
            }

            $this->entityManager->flush();
        }
    }

    public function deleteByMinerStock(MinerStock $minerStock): void
    {
        $this->entityManager
            ->getRepository($this->getEntityName())
            ->createQueryBuilder('a')
            ->delete()
            ->where('a.minerStock = :minerStock')
            ->setParameter('minerStock', $minerStock)
            ->getQuery()
            ->getResult();
    }

	/**
	 * @return string
	 */
	protected function getEntityName()
	{
		return MinerStockPool::class;
	}
}