<?php

namespace App\AddHash\AdminPanel\Infrastructure\Repository\User\Password;

use App\AddHash\AdminPanel\Domain\User\Password\UserPasswordRecovery;
use App\AddHash\AdminPanel\Domain\User\Password\UserPasswordRecoveryRepositoryInterface;
use App\AddHash\Authentication\Domain\Model\User;
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

	public function findByUser(User $user)
	{
		return $this->entityRepository->findOneBy(['user' => $user->getId()]);
	}

	/**
	 * @param null|string $hash
	 * @return null|object
	 */
	public function findByHash(?string $hash)
	{
		return $this->entityRepository->findOneBy(['hash' => $hash]);
	}

	/**
	 * @param UserPasswordRecovery $passwordRecovery
	 * @throws \Doctrine\ORM\ORMException
	 * @throws \Doctrine\ORM\OptimisticLockException
	 */
	public function remove(UserPasswordRecovery $passwordRecovery)
	{
		$this->entityManager->remove($passwordRecovery);
		$this->entityManager->flush();
	}

	/**
	 * @return string
	 */
	protected function getEntityName()
	{
		return UserPasswordRecovery::class;
	}

}