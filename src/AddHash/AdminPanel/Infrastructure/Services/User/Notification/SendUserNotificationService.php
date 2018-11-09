<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\Notification;

use App\AddHash\AdminPanel\Domain\User\Notification\UserNotificationDTO;
use App\AddHash\AdminPanel\Domain\User\User;
use App\AddHash\AdminPanel\Domain\User\Notification\UserNotification;
use App\AddHash\AdminPanel\Domain\User\Notification\UserNotificationRepositoryInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Notification\SendUserNotificationServiceInterface;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;

class SendUserNotificationService implements SendUserNotificationServiceInterface
{
	private $notificationRepository;
	private $producer;

	public function __construct(
		UserNotificationRepositoryInterface $notificationRepository,
		ProducerInterface $producer
	)
	{
		$this->notificationRepository = $notificationRepository;
		$this->producer = $producer;
	}

	/**
	 * @param string $title
	 * @param string $message
	 * @param User $user
	 */
	public function execute(string $title, string $message, User $user): void
	{
		$notification = new UserNotification($user, $title, $message);
		$this->notificationRepository->save($notification);

		$notificationDTO = new UserNotificationDTO($notification);
		$this->producer->publish($notificationDTO->getJsonMessage());

		return;
	}
}