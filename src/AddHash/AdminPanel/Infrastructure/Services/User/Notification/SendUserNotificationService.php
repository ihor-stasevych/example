<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\Notification;

use App\AddHash\AdminPanel\Domain\User\Notification\UserNotificationDTO;
use App\AddHash\AdminPanel\Domain\User\User;
use App\AddHash\AdminPanel\Domain\User\Notification\UserNotification;
use App\AddHash\AdminPanel\Domain\User\Notification\UserNotificationRepositoryInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Notification\SendUserNotificationServiceInterface;
use App\AddHash\System\GlobalContext\Common\QueueProducer;

#use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;

class SendUserNotificationService implements SendUserNotificationServiceInterface
{
	private $notificationRepository;
	private $producer;

	public function __construct(
		UserNotificationRepositoryInterface $notificationRepository,
		QueueProducer $producer
	)
	{
		$this->notificationRepository = $notificationRepository;
		$this->producer = $producer;
	}

	/**
	 * @param string $title
	 * @param string $message
	 * @param User $user
	 * @throws \Interop\Queue\Exception
	 * @throws \Interop\Queue\InvalidDestinationException
	 * @throws \Interop\Queue\InvalidMessageException
	 */
	public function execute(string $title, string $message, User $user): void
	{
		$notification = new UserNotification($user, $title, $message);
		$this->notificationRepository->save($notification);
		$notificationDTO = new UserNotificationDTO($notification);

		$this->producer->createTopic('user.notification.' . $user->getId())
			->createQueue('user.notification')
			->prepareMessage($notificationDTO->getJsonMessage())
			->send();

		return;
	}
}