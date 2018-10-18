<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\Notification;

use App\AddHash\AdminPanel\Domain\User\Notification\UserNotification;
use App\AddHash\AdminPanel\Domain\User\Services\UserGetAuthenticationServiceInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\AddHash\AdminPanel\Domain\User\Notification\UserNotificationRepositoryInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Notification\MarkAsReadNotificationServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Command\Notification\MarkAsReadNotificationCommandInterface;

class MarkAsReadNotificationService implements MarkAsReadNotificationServiceInterface
{
    private $notificationRepository;

    private $authenticationService;

	public function __construct(
		UserNotificationRepositoryInterface $notificationRepository,
        UserGetAuthenticationServiceInterface $authenticationService
	)
	{
		$this->notificationRepository = $notificationRepository;
		$this->authenticationService = $authenticationService;
	}

	/**
	 * @param MarkAsReadNotificationCommandInterface $command
	 * @return array|bool
	 */
	public function execute(MarkAsReadNotificationCommandInterface $command)
	{
        $user = $this->authenticationService->execute();

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