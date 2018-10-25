<?php

namespace App\AddHash\AdminPanel\Application\Command\User\Notification;

use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\AdminPanel\Domain\User\Command\Notification\MarkAsReadNotificationCommandInterface;

final class MarkAsReadNotificationCommand implements MarkAsReadNotificationCommandInterface
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