<?php

namespace App\AddHash\AdminPanel\Domain\Notification\Transport\Model;

interface NotificationTransportInterface
{
	public function prepareConnection();
	public function sendMessage($message);
}