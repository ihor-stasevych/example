<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\Notification;

use App\AddHash\AdminPanel\Domain\User\Command\Notification\MarkAsReadNotificationCommandInterface;
use App\AddHash\AdminPanel\Domain\User\Notification\UserNotification;
use App\AddHash\AdminPanel\Domain\User\Notification\UserNotificationRepositoryInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Notification\MarkAsReadNotificationServiceInterface;
use App\AddHash\AdminPanel\Domain\User\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MarkAsReadNotificationService implements MarkAsReadNotificationServiceInterface
{
	private $tokenStorage;
	private $notificationRepository;

	/**
	 * SendUserNotificationService constructor.
	 *
	 * @param TokenStorageInterface $tokenStorage
	 * @param UserNotificationRepositoryInterface $notificationRepository
	 */
	public function __construct(
		UserNotificationRepositoryInterface $notificationRepository,
		TokenStorageInterface $tokenStorage
	)
	{
		$this->notificationRepository = $notificationRepository;
		$this->tokenStorage = $tokenStorage;
	}

	/**
	 * @param MarkAsReadNotificationCommandInterface $command
	 * @return array|bool
	 */
	public function execute(MarkAsReadNotificationCommandInterface $command)
	{
		/** @var User $user */
		$user = $this->tokenStorage->getToken()->getUser();
		$notifications = $this->notificationRepository->findById($user, $command->getNotifications());

		if (empty($notifications)) {
			return [];
		}

		/** @var UserNotification $notification */
		foreach ($notifications as $notification) {
			$notification->updateStatus(UserNotification::STATUS_SEEN);
			$this->notificationRepository->save($notification);
		}

		return true;
	}
}