<?php

namespace App\AddHash\AdminPanel\Domain\User\Services\Notification;

interface SendUserNotificationServiceInterface
{
	public function execute(string $title, string $message);
}