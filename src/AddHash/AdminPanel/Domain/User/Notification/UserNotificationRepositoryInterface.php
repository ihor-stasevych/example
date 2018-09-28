<?php

namespace App\AddHash\AdminPanel\Domain\User\Notification;


use App\AddHash\AdminPanel\Domain\User\User;

interface UserNotificationRepositoryInterface
{
	public function save(UserNotification $notification);

	public function load(User $user, ?int $limit);

	public function findById($id);
}