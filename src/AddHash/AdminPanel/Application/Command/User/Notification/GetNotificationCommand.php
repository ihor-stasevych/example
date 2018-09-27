<?php

namespace App\AddHash\AdminPanel\Application\Command\User\Notification;

use App\AddHash\AdminPanel\Domain\User\Command\Notification\GetUserNotificationCommandInterface;

class GetNotificationCommand implements GetUserNotificationCommandInterface
{
	private $limit;

	public function __construct($limit = 25)
	{
		$this->limit = $limit;
	}

	public function getLimit()
	{
		return $this->limit;
	}
}