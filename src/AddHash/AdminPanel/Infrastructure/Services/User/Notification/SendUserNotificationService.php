<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\Notification;

use App\AddHash\AdminPanel\Domain\User\Notification\UserNotification;
use App\AddHash\AdminPanel\Domain\User\Services\UserGetAuthenticationServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Notification\UserNotificationRepositoryInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Notification\SendUserNotificationServiceInterface;

class SendUserNotificationService implements SendUserNotificationServiceInterface
{
	private $authenticationService;

	private $notificationRepository;

	public function __construct(
        UserGetAuthenticationServiceInterface $authenticationService,
		UserNotificationRepositoryInterface $notificationRepository
	)
	{
		$this->authenticationService = $authenticationService;
		$this->notificationRepository = $notificationRepository;
	}

	/**
	 * @param string $title
	 * @param string $message
	 * @return bool
	 */
	public function execute(string $title, string $message)
	{
        $user = $this->authenticationService->execute();

		$notification = new UserNotification($user, $title, $message);
		$this->notificationRepository->save($notification);

		return true;
	}
}