<?php

namespace App\AddHash\AdminPanel\Domain\User\Order;

use App\AddHash\AdminPanel\Domain\Miners\Miner;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;

class UserOrderMiner
{
	const STATE_READY = 1;
	const STATE_NOT_AVAILABLE = 2;

	const STATUS_PAUSED = 1;
	const STATUS_WORKING = 2;

	private $id;

	private $order;

	private $user;

	private $miner;

	private $createdAt;

	private $endPeriod;

	private $state;

	private $status;

	private $details;

	public function __construct(StoreOrder $order, Miner $miner, $endPeriod, $details = [])
	{
		$this->order = $order;
		$this->miner = $miner;
		$this->user = $order->getUser();
		$this->createdAt = new \DateTime();
		$this->endPeriod = $endPeriod;
		$this->details = $details;
		$this->state = self::STATE_READY;
		$this->status = self::STATUS_PAUSED;
	}

}