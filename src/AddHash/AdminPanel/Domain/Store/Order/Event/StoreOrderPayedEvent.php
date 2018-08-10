<?php

namespace App\AddHash\AdminPanel\Domain\Store\Order\Event;

use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Event;

class StoreOrderPayedEvent extends Event
{
	const NAME = 'store.order.payed';

	private $order;
	private $logger;

	public function __construct(StoreOrder $order, LoggerInterface $logger)
	{
		$this->order = $order;
		$this->logger = $logger;
	}

	public function getOrder()
	{
		return $this->order;
	}

	public function getLogger()
	{
		return $this->logger;
	}
}