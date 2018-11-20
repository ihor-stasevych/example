<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\Notification;

use App\AddHash\AdminPanel\Domain\User\Notification\UserNotificationDTO;
use App\AddHash\AdminPanel\Domain\User\User;
use App\AddHash\AdminPanel\Domain\User\Notification\UserNotification;
use App\AddHash\AdminPanel\Domain\User\Notification\UserNotificationRepositoryInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Notification\SendUserNotificationServiceInterface;
use Enqueue\Client\ProducerInterface;
use Enqueue\AmqpExt\AmqpContext;
use Interop\Amqp\Impl\AmqpBind;

#use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;

class SendUserNotificationService implements SendUserNotificationServiceInterface
{
	private $notificationRepository;
	private $producer;

	public function __construct(
		UserNotificationRepositoryInterface $notificationRepository,
		AmqpContext $producer
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
		//TODO::change it to normal
		$notificationDTO = new UserNotificationDTO($notification);
		//$this->producer->publish($notificationDTO->getJsonMessage(), 14);

		$context = $this->producer;
		$fooTopic = $this->producer->createTopic('user.notification.' . $user->getId());
		$fooTopic->setType(AMQP_EX_TYPE_FANOUT);
		$this->producer->declareTopic($fooTopic);

		$fooQueue = $context->createQueue('user.notification');
		$fooQueue->addFlag(AMQP_DURABLE);
		$context->declareQueue($fooQueue);

		$context->bind(new AmqpBind($fooTopic, $fooQueue));

		$this->producer->createProducer()->send($fooTopic, $this->producer->createMessage($notificationDTO->getJsonMessage()));

		return;
	}
}