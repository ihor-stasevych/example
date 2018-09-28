<?php

namespace App\AddHash\AdminPanel\Application\Command\User\Notification;

use App\AddHash\AdminPanel\Domain\User\Command\Notification\MarkAsReadNotificationCommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

class MarkAsReadNotificationCommand implements MarkAsReadNotificationCommandInterface
{
	/**
	 * @var array
	 * @Assert\NotBlank()
	 */
	private $notifications;

	public function __construct(?array $notifications)
	{
		$this->notifications = $notifications;
	}

	public function getNotifications()
	{
		return $this->notifications;
	}
}