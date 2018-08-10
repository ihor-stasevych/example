<?php

namespace App\AddHash\AdminPanel\Domain\Notification\Model;


interface NotificationInterface
{
	public function notify();

	public function prepareMessage();

	public function getTransport();
}