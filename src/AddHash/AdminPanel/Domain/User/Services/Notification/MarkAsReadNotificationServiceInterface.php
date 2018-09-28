<?php

namespace App\AddHash\AdminPanel\Domain\User\Services\Notification;

use App\AddHash\AdminPanel\Domain\User\Command\Notification\MarkAsReadNotificationCommandInterface;

interface MarkAsReadNotificationServiceInterface
{
	public function execute(MarkAsReadNotificationCommandInterface $command);
}