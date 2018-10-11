<?php

namespace App\AddHash\AdminPanel\Infrastructure\Repository\User\Notification;

use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use App\AddHash\Authentication\Domain\Model\User;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;
use App\AddHash\AdminPanel\Domain\User\Notification\UserNotification;
use App\AddHash\AdminPanel\Domain\User\Notification\UserNotificationRepositoryInterface;

class UserNotificationRepository extends AbstractRepository implements UserNotificationRepositoryInterface
{
	/**
	 * @param UserNotification $notification
	 * @throws ORMException
	 * @throws OptimisticLockException
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

	public function findById(User $user, $id)
	{
		return $this->entityRepository->findBy([
		    'id'   => $id,
            'user' => $user->getId()
        ]);
	}

	protected function getEntityName()
	{
		return UserNotification::class;
	}
}