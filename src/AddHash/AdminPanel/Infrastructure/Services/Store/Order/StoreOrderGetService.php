<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Store\Order;

use App\AddHash\AdminPanel\Domain\Notification\Model\Notification;
use App\AddHash\AdminPanel\Domain\Notification\Transport\Model\NotificationTransportTelegramModel;
use App\AddHash\AdminPanel\Domain\Store\Order\Event\StoreOrderPayedEvent;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Services\StoreOrderGetServiceInterface;
use App\AddHash\AdminPanel\Domain\User\User;
use App\AddHash\AdminPanel\Infrastructure\Notification\Transport\NotificationTransportTelegram;
use App\AddHash\AdminPanel\Infrastructure\Services\Notification\SendNotificationMessageService;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\EventDispatcher\EventDispatcher;

class StoreOrderGetService implements StoreOrderGetServiceInterface
{

	private $storeOrderRepository;
	private $dispatcher;

	public function __construct(
		StoreOrderRepositoryInterface $orderRepository,
		EventDispatcher $dispatcher
	)
	{
		$this->storeOrderRepository = $orderRepository;
		$this->dispatcher = $dispatcher;
	}

	public function execute(User $user)
	{
		$this->storeOrderRepository->findNewByUserId($user->getId());
	}
}