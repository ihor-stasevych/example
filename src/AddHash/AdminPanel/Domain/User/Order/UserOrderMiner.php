<?php

namespace App\AddHash\AdminPanel\Domain\User\Order;

use App\AddHash\AdminPanel\Domain\Miners\Miner;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use Doctrine\Common\Collections\ArrayCollection;

class UserOrderMiner
{
	const STATE_READY = 1;
	const STATE_NOT_AVAILABLE = 2;

	const STATUS_PAUSED = 1;
	const STATUS_WORKING = 2;

	private $id;

	private $order;

	private $user;

	/** @var ArrayCollection  */
	private $miner;

	private $createdAt;

	private $endPeriod;

	private $details;

	public function __construct(StoreOrder $order, $endPeriod, $miners = [], $details = [])
	{
		$this->order = $order;
		$this->user = $order->getUser();
		$this->createdAt = new \DateTime();
		$this->endPeriod = $endPeriod;
		$this->details = $details;
		$this->miner = new ArrayCollection();
		$this->setMiners($miners);
	}

	public function setMiners($miners)
	{
		foreach ($miners as $miner) {
			$this->setMiner($miner);
		}
	}

	public function setMiner(Miner $miner)
	{
		if (!$this->miner->contains($miner)) {
			$this->miner->add($miner);
		}
	}

	public function getMiners()
    {
        return $this->miner;
    }
}