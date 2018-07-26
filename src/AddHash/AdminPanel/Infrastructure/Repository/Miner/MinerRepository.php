<?php

namespace App\AddHash\AdminPanel\Infrastructure\Repository\Miner;


use App\AddHash\AdminPanel\Domain\Miners\Miner;
use App\AddHash\AdminPanel\Domain\Miners\Repository\MinerRepositoryInterface;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;

class MinerRepository extends AbstractRepository implements MinerRepositoryInterface
{
	/**
	 * @param Miner $miner
	 * @throws \Doctrine\ORM\ORMException
	 * @throws \Doctrine\ORM\OptimisticLockException
	 */
	public function save(Miner $miner)
	{
		$this->entityManager->persist($miner);
		$this->entityManager->flush($miner);
	}

	/**
	 * @return string
	 */
	protected function getEntityName()
	{
		return Miner::class;
	}
}