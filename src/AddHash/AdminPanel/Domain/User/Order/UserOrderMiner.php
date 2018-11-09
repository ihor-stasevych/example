<?php

namespace App\AddHash\AdminPanel\Domain\User\Order;

use App\AddHash\AdminPanel\Domain\Miners\Miner;
use App\AddHash\AdminPanel\Domain\Miners\MinerStock;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use App\AddHash\AdminPanel\Domain\User\User;
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

	private $minerStock;

	/** @var \DateTime  */
	private $createdAt;

	/** @var \DateTime */
	private $endPeriod;

	private $details;

	public function __construct(StoreOrder $order, $endPeriod, $minerStocks = [], $details = [])
	{
		$this->order = $order;
		$this->user = $order->getUser();
		$this->createdAt = new \DateTime();
		$this->endPeriod = $endPeriod;
		$this->details = json_encode($details);
		$this->minerStock = new ArrayCollection();
		$this->setMiners($minerStocks);
	}

	public function setMiners($minerStocks)
	{
		foreach ($minerStocks as $stock) {
			$this->setMiner($stock);
		}
	}

	public function setMiner(MinerStock $minerStock)
	{
		if (!$this->minerStock->contains($minerStock)) {
			$this->minerStock->add($minerStock);
		}
	}

	public function getMiners()
    {
        return $this->minerStock;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getRentPeriod()
    {
    	return $this->createdAt->format('Y-d-m H:i') . ' - ' . $this->endPeriod->format('Y-d-m H:i');
    }
}