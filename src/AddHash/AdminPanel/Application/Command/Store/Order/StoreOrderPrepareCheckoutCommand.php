<?php

namespace App\AddHash\AdminPanel\Application\Command\Store\Order;

use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\AdminPanel\Domain\Store\Order\Command\StoreOrderPrepareCheckoutCommandInterface;

class StoreOrderPrepareCheckoutCommand implements StoreOrderPrepareCheckoutCommandInterface
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