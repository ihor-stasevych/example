<?php

namespace App\AddHash\AdminPanel\Domain\Store\Order\Item;


use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use App\AddHash\AdminPanel\Domain\Store\Product\StoreProduct;
use Doctrine\Common\Collections\ArrayCollection;

class StoreOrderItem
{
	private $id;

	private $createdAt;

	private $updatedAt;

	private $quantity;

	private $priceTotal;

	private $order;

	/**
	 * @var StoreProduct
	 */
	private $product;

	public function __construct(
		StoreOrder $order, StoreProduct $product,
		int $quantity
	)
	{
		$this->quantity = 1;
		$this->createdAt = new \DateTime();
		$this->setOrder($order);
		$this->addProduct($product);
		$this->setQuantity($quantity);
		$this->priceTotal = $this->quantity * $product->getPrice();
	}

	public function getId()
	{
		return $this->id;
	}

	public function setOrder(StoreOrder $order)
	{
		$this->order = $order;
	}

	public function addProduct(StoreProduct $product)
	{
		$this->product = $product;
	}

	public function getProduct()
	{
		return $this->product;
	}

	public function addQuantity()
	{
		$this->quantity += 1;
	}

	public function calculateTotalPrice()
	{
		$this->priceTotal = $this->product->getPrice() * $this->quantity;
	}

	public function getQuantity()
	{
		return $this->quantity;
	}

	public function getTotalPrice()
	{
		return $this->priceTotal;
	}

	public function setQuantity(int $quantity = 1)
	{
		$this->quantity = $quantity;
	}
}