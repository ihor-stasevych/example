<?php

namespace App\AddHash\AdminPanel\Application\Command\Store\Order;

use App\AddHash\AdminPanel\Domain\Store\Order\Command\StoreOrderAddProductCommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

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


	/**
	 * StoreOrderAddProductCommand constructor.
	 *
	 * @param string $order
	 * @param string $product
	 */
	public function __construct($order, $product)
	{
		$this->order = $order;
		$this->product = $product;
	}

	/**
	 * @return string
	 */
	public function getOrder()
	{
		return $this->order;
	}

	/**
	 * @return string
	 */
	public function getProduct()
	{
		return $this->product;
	}
}