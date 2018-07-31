<?php

namespace App\AddHash\AdminPanel\Infrastructure\Repository\User\Order\Miner;


use App\AddHash\AdminPanel\Domain\User\Order\UserOrderMiner;
use App\AddHash\AdminPanel\Domain\User\Order\UserOrderMinerRepositoryInterface;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;

class UserOrderMinerRepository extends AbstractRepository implements UserOrderMinerRepositoryInterface
{

	/**
	 * @param UserOrderMiner $userOrderMiner
	 * @throws \Doctrine\ORM\ORMException
	 * @throws \Doctrine\ORM\OptimisticLockException
	 */
	public function save(UserOrderMiner $userOrderMiner)
	{
		$this->entityManager->persist($userOrderMiner);
		$this->entityManager->flush($userOrderMiner);
	}

	/**
	 * @return string
	 */
	protected function getEntityName()
	{
		return UserOrderMiner::class;
	}
}