<?php

namespace App\AddHash\AdminPanel\Domain\Notification\Model;

use App\AddHash\AdminPanel\Domain\Notification\Transport\Model\NotificationTransportInterface;

class Notification implements NotificationInterface
{
	private $message;
	private $transport;

	public function __construct(NotificationTransportInterface $transport)
	{
		$this->transport = $transport;
	}

	public function notify()
	{
		return $this->transport->sendMessage($this->prepareMessage());
	}

	public function prepareMessage()
	{
		return $this->message;
	}

	public function getTransport()
	{
		return $this->transport;
	}

	public function setMessage(string $message)
	{
		$this->message = $message;
	}

	public function setTransport(NotificationTransportInterface $transport)
	{
		$this->transport = $transport;
	}
}