<?php

namespace App\AddHash\AdminPanel\Domain\User\Services\Notification;

use App\AddHash\AdminPanel\Domain\User\Command\Notification\GetUserNotificationCommandInterface;

interface GetUserNotificationServiceInterface
{
	public function execute(GetUserNotificationCommandInterface $command);
}