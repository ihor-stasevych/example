<?php

namespace App\AddHash\AdminPanel\Domain\User\Services\Notification;

use App\AddHash\AdminPanel\Domain\User\User;

interface SendUserNotificationServiceInterface
{
	public function execute(string $title, string $message, User $user): void;
}