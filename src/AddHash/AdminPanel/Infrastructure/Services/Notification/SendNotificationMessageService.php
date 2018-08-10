<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Notification;

use App\AddHash\AdminPanel\Domain\Notification\Model\Notification;
use App\AddHash\AdminPanel\Domain\Notification\Model\NotificationInterface;
use App\AddHash\AdminPanel\Domain\Notification\Services\SendNotificationMessageServiceInterface;
use App\AddHash\AdminPanel\Domain\Notification\Transport\Model\NotificationTransportInterface;

class SendNotificationMessageService implements SendNotificationMessageServiceInterface
{
	private $notification;

	/**
	 * SendNotificationMessageService constructor.
	 *
	 * @param NotificationInterface $notification
	 */
	public function __construct(NotificationInterface $notification)
	{
		$this->notification = $notification;
	}

	/**
	 * @param $message
	 */
	public function execute($message)
	{
		return $this->notification->notify();
	}
}