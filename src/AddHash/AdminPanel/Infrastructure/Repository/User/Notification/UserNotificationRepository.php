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

	/**
	 * @param User $user
	 * @param int $limit
	 * @return mixed
	 */
	public function getNew(User $user, ?int $limit = 25)
	{
		return $this->entityRepository->createQueryBuilder('un')
			->select('un')
			->where('un.status = :status')
			->andWhere('un.user = :userId')
			->setMaxResults($limit)
			->setParameter('status', UserNotification::STATUS_NEW)
			->setParameter('userId', $user->getId())
			->getQuery()
			->getResult();
	}

	protected function getEntityName()
	{
		return UserNotification::class;
	}
}