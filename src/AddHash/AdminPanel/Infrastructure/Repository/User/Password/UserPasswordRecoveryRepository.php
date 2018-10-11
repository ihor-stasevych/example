<?php

namespace App\AddHash\AdminPanel\Infrastructure\Repository\User\Password;

use App\AddHash\AdminPanel\Domain\User\Password\UserPasswordRecovery;
use App\AddHash\AdminPanel\Domain\User\Password\UserPasswordRecoveryRepositoryInterface;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;

class UserPasswordRecoveryRepository extends AbstractRepository implements UserPasswordRecoveryRepositoryInterface
{
	/**
	 * @param UserPasswordRecovery $passwordRecovery
	 * @throws \Doctrine\ORM\ORMException
	 * @throws \Doctrine\ORM\OptimisticLockException
	 */
	public function save(UserPasswordRecovery $passwordRecovery)
	{
		$this->entityManager->persist($passwordRecovery);
		$this->entityManager->flush();
	}

	/**
	 * @param null|string $hash
	 * @return array|object[]
	 */
	public function findByHash(?string $hash)
	{
		return $this->entityRepository->findBy(['hash' => $hash]);
	}

	/**
	 * @return string
	 */
	protected function getEntityName()
	{
		return UserPasswordRecovery::class;
	}

}