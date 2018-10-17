<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\Notification;

use App\AddHash\AdminPanel\Domain\User\Services\UserGetAuthenticationServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Notification\UserNotificationRepositoryInterface;
use App\AddHash\AdminPanel\Domain\User\Command\Notification\GetUserNotificationCommandInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Notification\GetUserNotificationServiceInterface;

class GetUserNotificationService implements GetUserNotificationServiceInterface
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

	public function execute(GetUserNotificationCommandInterface $command)
	{
        $user = $this->authenticationService->execute();

		return $this->notificationRepository->load($user, $command->getLimit());
	}
}