<?php

namespace App\AddHash\AdminPanel\Application\Command\User\Notification;

use App\AddHash\AdminPanel\Domain\User\Command\Notification\SendUserNotificationCommandInterface;

final class SendNotificationCommand implements SendUserNotificationCommandInterface
{
	private $message;

	public function getMessage()
	{
		return $this->message;
	}
}