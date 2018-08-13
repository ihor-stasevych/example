<?php

namespace App\AddHash\AdminPanel\Domain\Notification\Services;

interface SendNotificationMessageServiceInterface
{
	public function execute($message);
}