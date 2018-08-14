<?php

namespace App\AddHash\AdminPanel\Infrastructure\Repository\Miner;

use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use App\AddHash\AdminPanel\Domain\Miners\MinerStock;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;
use App\AddHash\AdminPanel\Domain\Miners\Repository\MinerStockRepositoryInterface;

class MinerStockRepository extends AbstractRepository implements MinerStockRepositoryInterface
{
    /**
     * @param MinerStock $minerStock
     * @throws ORMException
     * @throws OptimisticLockException
     */
	public function save(MinerStock $minerStock)
	{
		$this->entityManager->persist($minerStock);
		$this->entityManager->flush($minerStock);
	}

	/**
	 * @return string
	 */
	protected function getEntityName()
	{
		return MinerStock::class;
	}
}