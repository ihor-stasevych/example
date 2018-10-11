<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\Notification;

use App\AddHash\Authentication\Domain\Model\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\AddHash\AdminPanel\Domain\User\Notification\UserNotificationRepositoryInterface;
use App\AddHash\AdminPanel\Domain\User\Command\Notification\GetUserNotificationCommandInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Notification\GetUserNotificationServiceInterface;

class GetUserNotificationService implements GetUserNotificationServiceInterface
{
	private $tokenStorage;

	private $notificationRepository;

	public function __construct(
		TokenStorageInterface $tokenStorage,
		UserNotificationRepositoryInterface $notificationRepository
	)
	{
		$this->tokenStorage = $tokenStorage;
		$this->notificationRepository = $notificationRepository;
	}

	public function execute(GetUserNotificationCommandInterface $command)
	{
		/** @var User $user */
		$user = $this->tokenStorage->getToken()->getUser();

		return $this->notificationRepository->load($user, $command->getLimit());
	}
}