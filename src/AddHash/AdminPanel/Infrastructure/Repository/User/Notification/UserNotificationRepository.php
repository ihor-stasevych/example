<?php

namespace App\AddHash\AdminPanel\Infrastructure\Repository\User\Notification;

use App\AddHash\AdminPanel\Domain\User\Notification\UserNotification;
use App\AddHash\AdminPanel\Domain\User\Notification\UserNotificationRepositoryInterface;
use App\AddHash\AdminPanel\Domain\User\User;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;

class UserNotificationRepository extends AbstractRepository implements UserNotificationRepositoryInterface
{

	/**
	 * @param UserNotification $notification
	 * @throws \Doctrine\ORM\ORMException
	 * @throws \Doctrine\ORM\OptimisticLockException
	 */
	public function save(UserNotification $notification)
	{
		$this->entityManager->persist($notification);
		$this->entityManager->flush();
	}


	public function load(User $user, ?int $limit = 100)
	{
		return $this->entityRepository->createQueryBuilder('un')
			->select('un')
			->where('un.user = :userId')
			->setMaxResults($limit)
			->orderBy('un.created', 'desc')
			->setParameter('userId', $user->getId())
			->getQuery()
			->getResult();
	}

	public function findById($id)
	{
		return $this->entityRepository->findBy(['id' => $id]);
	}

	protected function getEntityName()
	{
		return UserNotification::class;
	}
}