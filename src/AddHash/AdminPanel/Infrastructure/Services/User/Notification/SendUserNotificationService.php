<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\Notification;

use App\AddHash\Authentication\Domain\Model\User;
use App\AddHash\AdminPanel\Domain\User\Notification\UserNotification;
use App\AddHash\AdminPanel\Domain\User\Notification\UserNotificationRepositoryInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Notification\SendUserNotificationServiceInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SendUserNotificationService implements SendUserNotificationServiceInterface
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
		TokenStorageInterface $tokenStorage,
		UserNotificationRepositoryInterface $notificationRepository
	)
	{
		$this->tokenStorage = $tokenStorage;
		$this->notificationRepository = $notificationRepository;
	}

	/**
	 * @param string $title
	 * @param string $message
	 * @return bool
	 */
	public function execute(string $title, string $message)
	{
		/** @var User $user */
		$user = $this->tokenStorage->getToken()->getUser();
		$notification = new UserNotification($user, $title, $message);
		$this->notificationRepository->save($notification);

		return true;
	}
}