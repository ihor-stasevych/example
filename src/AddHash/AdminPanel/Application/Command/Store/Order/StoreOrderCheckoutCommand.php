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

	/**
	 * @var string
	 * @Assert\Type(type="string")
	 * @Assert\NotNull()
	 */
	private $token;


	public function __construct($order, $token)
	{
		$this->order = $order;
		$this->token = $token;
	}

	public function getOrder()
	{
		return $this->order;
	}

	public function getToken()
	{
		return $this->token;
	}
}