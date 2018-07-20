<?php

namespace App\AddHash\AdminPanel\Application\Command\Store\Order;

use App\AddHash\AdminPanel\Domain\Store\Order\Command\StoreOrderCheckoutCommandOrderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class StoreOrderCheckoutCommand implements StoreOrderCheckoutCommandOrderInterface
{

	/**
	 * @var string
	 * @Assert\Type(type="string")
	 * @Assert\NotNull()
	 */
	private $order;


	public function __construct($order)
	{
		$this->order = $order;
	}

	public function getOrder()
	{
		return $this->order;
	}
}