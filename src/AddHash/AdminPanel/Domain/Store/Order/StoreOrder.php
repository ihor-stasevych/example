<?php

namespace App\AddHash\AdminPanel\Domain\Store\Order;

use App\AddHash\AdminPanel\Domain\Store\Order\Item\StoreOrderItem;
use App\AddHash\AdminPanel\Domain\Store\Product\StoreProduct;
use App\AddHash\AdminPanel\Domain\User\User;
use Doctrine\Common\Collections\ArrayCollection;

class StoreOrder
{
	const STATE_NEW = 1;
	const STATE_PAYED = 2;
	const STATE_CLOSED = 3;

	private $id;

	private $createdAt;

	private $updatedAt;

	private $itemsTotal;

	private $itemsPriceTotal;

	private $state;

	/** @var ArrayCollection */
	private $items;

	private $user;


	public function __construct(User $user)
	{
		$this->createdAt = new \DateTime();
		$this->updatedAt = new \DateTime();
		$this->items = new ArrayCollection();
		$this->state = self::STATE_NEW;
		$this->user = $user;
	}

	public function addItem(StoreOrderItem $item)
	{
		if (!$this->items->contains($item)) {
			$this->items->add($item);
			$this->itemsTotal++;
			$this->itemsPriceTotal += $item->getProduct()->getPrice();
		}
	}

	public function getId()
	{
		return $this->id;
	}

	public function getItems()
	{
		return $this->items;
	}

	public function getItemsPriceTotal()
	{
		return $this->itemsPriceTotal;
	}

	public function productContains(StoreProduct $product)
	{
		/** @var StoreOrderItem $item */
		foreach ($this->getItems() as $item) {
			if ($item->getProduct() === $product) {
				return true;
			}
		}

		return false;
	}

	public function indexOfProduct(StoreProduct $product)
	{
		/** @var StoreOrderItem $item */
		foreach ($this->getItems() as $key => $item) {
			if ($item->getProduct() === $product) {
				return $key;
			}
		}

		return false;
	}

	public function getState()
	{
		return $this->state;
	}

	public function ensureAvailableProductMiners()
	{
		$result = [];

		/** @var StoreOrderItem $item */
		foreach ($this->getItems() as $key => $item) {
			$result[] = $item->getProduct()->ensureAvailableMiner($item->getQuantity());
		}

		return $result;
	}

	public function checkAvailableMiners()
	{
		/** @var StoreOrderItem $item */
		foreach ($this->getItems() as $item) {
			if ($item->getProduct()->getAvailableMinersQuantity() < $item->getQuantity()) {
				return false;
			}
		}

		return true;
	}

	public function calculateItems()
	{
		$totalPrice = 0;
		$count = 0;

		/** @var StoreOrderItem $item */
		foreach ($this->getItems() as $item) {
			$count += $item->getQuantity();
			$totalPrice += $item->getTotalPrice();
		}

		$this->itemsPriceTotal = $totalPrice;
		$this->itemsTotal = $count;
	}

	public function getUser()
	{
		return $this->user;
	}

	public function setPayedState()
	{
		$this->state = self::STATE_PAYED;
	}
}