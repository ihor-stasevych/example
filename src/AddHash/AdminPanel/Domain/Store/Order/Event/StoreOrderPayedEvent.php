<?php

namespace App\AddHash\AdminPanel\Domain\Store\Order\Event;

use App\AddHash\AdminPanel\Domain\Notification\Model\NotificationInterface;
use App\AddHash\AdminPanel\Domain\Notification\Services\SendNotificationMessageServiceInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Event;

class StoreOrderPayedEvent extends Event
{
	const NAME = 'store.order.payed';

	private $order;
	private $logger;
	private $notificationService;

	public function __construct(
		StoreOrder $order,
		NotificationInterface $notificationService)
	{
		$this->order = $order;
		$this->notificationService = $notificationService;
		//$this->logger = $logger;
	}

	public function getOrder()
	{
		return $this->order;
	}

	/**
	 * @return NotificationInterface
	 */
	public function getNotificationService()
	{
		return $this->notificationService;
	}

	public function getLogger()
	{
		return $this->logger;
	}
}