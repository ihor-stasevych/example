<?php

namespace App\AddHash\AdminPanel\Domain\User\Notification;

final class UserNotificationDTO
{
	private $notification;

	/**
	 * UserNotificationDTO constructor.
	 *
	 * @param UserNotification $notification
	 */
	public function __construct(UserNotification $notification)
	{
		$this->notification = $notification;
	}

	/**
	 * @return string
	 */
	public function getJsonMessage()
	{
		return json_encode([
			'title' => $this->notification->getTitle(),
			'message' => $this->notification->getMessage(),
			'time' => $this->notification->getCreated(),
			'userId' => $this->notification->ensureUser()->getId()
		]);
	}
}