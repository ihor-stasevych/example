<?php

namespace App\AddHash\AdminPanel\Domain\User\Command\Notification;

interface SendUserNotificationCommandInterface
{
	public function getMessage();
}