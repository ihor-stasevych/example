<?php

namespace App\AddHash\AdminPanel\Domain\User\Notification;

use App\AddHash\Authentication\Domain\Model\User;

interface UserNotificationRepositoryInterface
{
	public function save(UserNotification $notification);

	public function load(User $user, ?int $limit);

	public function findById(User $user, $id);
}