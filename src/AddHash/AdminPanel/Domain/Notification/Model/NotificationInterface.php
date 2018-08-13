<?php

namespace App\AddHash\AdminPanel\Domain\Notification\Model;


use App\AddHash\AdminPanel\Domain\Notification\Transport\Model\NotificationTransportInterface;

interface NotificationInterface
{
	public function notify();

	public function prepareMessage();

	public function getTransport();

	public function setTransport(NotificationTransportInterface $transport);

	public function setMessage(string $message);
}