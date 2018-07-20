<?php

namespace App\AddHash\AdminPanel\Application\Command\Store\Order;

use App\AddHash\AdminPanel\Domain\Store\Order\Command\StoreOrderAddProductInterface;

class StoreOrderAddProduct implements StoreOrderAddProductInterface
{
	private $product;

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