<?php

namespace App\AddHash\AdminPanel\Domain\Store\Order;

use App\AddHash\AdminPanel\Domain\Payment\Payment;
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

	private $payment;


	/**
	 * StoreOrder constructor.
	 *
	 * @param User $user
	 */
	public function __construct(User $user)
	{
		$this->createdAt = new \DateTime();
		$this->updatedAt = new \DateTime();
		$this->items = new ArrayCollection();
		$this->state = self::STATE_NEW;
		$this->user = $user;
	}

	/**
	 * @param StoreOrderItem $item
	 */
	public function addItem(StoreOrderItem $item)
	{
		if (!$this->items->contains($item)) {
			$this->items->add($item);
			$this->itemsTotal++;
			$this->itemsPriceTotal += $item->getProduct()->getPrice();
		}
	}

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return ArrayCollection
	 */
	public function getItems()
	{
		return $this->items;
	}

	/**
	 * @return mixed
	 */
	public function getItemsPriceTotal()
	{
		return $this->itemsPriceTotal;
	}

	/**
	 * @param StoreProduct $product
	 * @return bool
	 */
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

	/**
	 * @param StoreProduct $product
	 * @return bool|int|mixed|string
	 */
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

	/**
	 * @return int
	 */
	public function getState()
	{
		return $this->state;
	}

	/**
	 * @return array
	 */
	public function ensureAvailableProductMiners()
	{
		$result = [];

		/** @var StoreOrderItem $item */
		foreach ($this->getItems() as $key => $item) {
			$result[] = $item->getProduct()->ensureAvailableMiner($item->getQuantity());
		}

		return $result;
	}

	/**
	 * @return bool
	 */
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

	/**
	 * @return bool
	 */
	public function checkReservedMiners()
	{
		/** @var StoreOrderItem $item */
		foreach ($this->getItems() as $item) {
			if ($item->getProduct()->getReservedMinersQuantity() < $item->getQuantity()) {
				return false;
			}
		}

		return true;
	}

	/**
	 * @inheritdoc
	 * Calculate price of all items
	 */
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

	/**
	 * @return User
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * @inheritdoc
	 */
	public function setPayedState()
	{
		$this->state = self::STATE_PAYED;
	}


	/**
	 * @param StoreProduct $product
	 * @return StoreOrderItem|bool
	 */
	public function addProductItem(StoreProduct $product)
	{
		if ($product->getAvailableMinersQuantity() == 0) {
			return false;
		}

		if (!$this->productContains($product)) {
			$item = new StoreOrderItem($this, $product);
			$this->addItem($item);
		} else {
			$key = $this->indexOfProduct($product);
			/** @var StoreOrderItem $item */
			$item = $this->getItems()->get($key);
			$item->addQuantity();
			$item->calculateTotalPrice();
		}

		return $item;
	}

	/**
	 * @param StoreProduct $product
	 * @return StoreOrderItem|bool
	 */
	public function removeItemByProduct(StoreProduct $product)
	{
		if (!$this->productContains($product)) {
			return false;
		}

		$key = $this->indexOfProduct($product);

		/** @var StoreOrderItem $item */
		$item = $this->getItems()->get($key);
		$this->items->removeElement($item);
		return $item;
	}

	/**
	 * @param StoreOrderItem $item
	 */
	public function removeItem(StoreOrderItem $item)
	{
		$this->items->removeElement($item);
	}

	/**
	 * @inheritdoc
	 */
	public function closeOrder()
	{
		$this->state = self::STATE_CLOSED;
	}

	/**
	 * @param Payment $payment
	 */
	public function setPayment(Payment $payment)
	{
		$this->payment = $payment;
	}
}