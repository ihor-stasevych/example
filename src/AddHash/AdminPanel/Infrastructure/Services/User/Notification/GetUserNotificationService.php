<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\Notification;

use App\AddHash\AdminPanel\Domain\User\Command\Notification\GetUserNotificationCommandInterface;
use App\AddHash\AdminPanel\Domain\User\Notification\UserNotificationRepositoryInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Notification\GetUserNotificationServiceInterface;
use App\AddHash\AdminPanel\Domain\User\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class GetUserNotificationService implements GetUserNotificationServiceInterface
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
	 * @param GetUserNotificationCommandInterface $command
	 * @return mixed
	 */
	public function execute(GetUserNotificationCommandInterface $command)
	{
		/** @var User $user */
		$user = $this->tokenStorage->getToken()->getUser();
		return $this->notificationRepository->load($user, $command->getLimit());
	}
}