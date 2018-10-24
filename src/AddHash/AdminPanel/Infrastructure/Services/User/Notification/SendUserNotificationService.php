<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\Notification;

use App\AddHash\AdminPanel\Domain\User\User;
use App\AddHash\AdminPanel\Domain\User\Notification\UserNotification;
use App\AddHash\AdminPanel\Domain\User\Notification\UserNotificationRepositoryInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Notification\SendUserNotificationServiceInterface;

class SendUserNotificationService implements SendUserNotificationServiceInterface
{
	private $notificationRepository;

	public function __construct(UserNotificationRepositoryInterface $notificationRepository)
	{
		$this->notificationRepository = $notificationRepository;
	}

	public function execute(string $title, string $message, User $user): void
	{
		$notification = new UserNotification($user, $title, $message);
		$this->notificationRepository->save($notification);
	}
}