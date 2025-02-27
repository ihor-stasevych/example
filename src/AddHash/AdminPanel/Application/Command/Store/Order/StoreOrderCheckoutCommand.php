<?php

namespace App\AddHash\AdminPanel\Application\Command\Store\Order;

use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\AdminPanel\Domain\Store\Order\Command\StoreOrderCheckoutCommandInterface;

class StoreOrderCheckoutCommand implements StoreOrderCheckoutCommandInterface
{
	/**
	 * @var string
	 * @Assert\Type(type="string")
	 * @Assert\NotNull()
	 */
	private $token;

	public function __construct($token)
	{
		$this->token = $token;
	}

	public function getToken()
	{
		return $this->token;
	}
}