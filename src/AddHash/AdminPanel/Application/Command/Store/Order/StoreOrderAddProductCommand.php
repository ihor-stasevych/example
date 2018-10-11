<?php

namespace App\AddHash\AdminPanel\Application\Command\Store\Order;

use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\AdminPanel\Domain\Store\Order\Command\StoreOrderAddProductCommandInterface;

class StoreOrderAddProductCommand implements StoreOrderAddProductCommandInterface
{
	/**
	 * @var string
	 * @Assert\NotNull()
	 */
	private $product;

	/**
	 * @var string
	 * @Assert\NotNull()
	 */
	private $order;

	public function __construct($order, $product)
	{
		$this->order = $order;
		$this->product = $product;
	}

	public function getOrder()
	{
		return $this->order;
	}

	public function getProduct()
	{
		return $this->product;
	}
}