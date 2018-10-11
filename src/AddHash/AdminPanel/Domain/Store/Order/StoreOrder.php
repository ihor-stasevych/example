<?php

namespace App\AddHash\AdminPanel\Domain\Store\Order;

use App\AddHash\AdminPanel\Domain\Payment\Payment;
use App\AddHash\AdminPanel\Domain\Store\Order\Item\StoreOrderItem;
use App\AddHash\AdminPanel\Domain\Store\Product\StoreProduct;
use App\AddHash\Authentication\Domain\Model\User;
use Doctrine\Common\Collections\ArrayCollection;

class StoreOrder
{
	const STATE_NEW = 1;
	const STATE_PAYED = 2;
	const STATE_CLOSED = 3;

	const STATE_ALIAS = [
        self::STATE_NEW    => 'New',
        self::STATE_PAYED  => 'Payed',
        self::STATE_CLOSED => 'Closed',
    ];

	const STATES = [
	    self::STATE_NEW,
        self::STATE_PAYED,
        self::STATE_CLOSED,
    ];

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
     * @param User $user
     * @param null $id
     */
	public function __construct(User $user, $id = null)
	{
		$this->createdAt = new \DateTime();
		$this->updatedAt = new \DateTime();
		$this->items = new ArrayCollection();
		$this->state = self::STATE_NEW;
		$this->user = $user;
		$this->id = $id;
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

	public function getStateAlias()
    {
        return static::STATE_ALIAS[$this->state];
    }

	/**
	 * @return mixed
	 */
	public function getItemsPriceTotal()
	{
		return $this->itemsPriceTotal;
	}

	public function getCreatedAt()
    {
        return $this->createdAt;
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

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

	/**
	 * @inheritdoc
	 */
	public function setPayedState()
	{
		$this->state = self::STATE_PAYED;
	}


	/**
	 * Do not touch this shit
	 * @param StoreProduct $product
	 * @param int $quantity
	 * @return StoreOrderItem|bool
	 */
	public function addProductItem(StoreProduct $product, $quantity = 0)
	{
		if (!$this->productContains($product)) {

			if ($product->getAvailableMinersQuantity() < $quantity) {
				return false;
			}

			$item = new StoreOrderItem($this, $product, $quantity);
			$this->addItem($item);
		} else {
			$key = $this->indexOfProduct($product);
			/** @var StoreOrderItem $item */
			$item = $this->getItems()->get($key);

			if ($item->getQuantity() < $quantity) {
				if ($product->getAvailableMinersQuantity() < $quantity) {
					return false;
				}
			}

			$item->setQuantity($quantity);
		}

		$item->calculateTotalPrice();

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

	public function removeItems()
	{
		/** @var StoreOrderItem  $item */
		foreach ($this->getItems() as $item) {
			$item->getProduct()->unReserveMiner();
			$this->items->removeElement($item);
		}
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