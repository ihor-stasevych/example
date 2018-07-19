<?php

namespace App\AddHash\AdminPanel\Domain\Store\Product;


use App\AddHash\AdminPanel\Domain\Miners\Miner;
use App\AddHash\AdminPanel\Domain\Store\Category\Model\StoreCategory;
use Doctrine\Common\Collections\ArrayCollection;

class StoreProduct
{
	const STATE_UNAVAILABLE = 0;
	const STATE_AVAILABLE = 1;

	private $id;

	private $title;

	private $description;

	private $techDetails;

	private $price;

	private $state;

	private $createdAt;

	protected $statusAlias = [
		self::STATE_AVAILABLE => 'available',
		self::STATE_UNAVAILABLE => 'unavailable'
	];

	/**
	 * @var ArrayCollection
	 */
	private $category;

	/**
	 * @var ArrayCollection
	 */
	private $media;

	/**
	 * @var ArrayCollection
	 */
	private $miner;

	public function __construct(
		$title, $description, $techDetails,
		$price, $state, $categories
	)
	{
		$this->title = $title;
		$this->description = $description;
		$this->techDetails = $techDetails;
		$this->price = $price;
		$this->state = $state;
		$this->createdAt = time();
		$this->category = new ArrayCollection();
		$this->media = new ArrayCollection();
		$this->miner = new ArrayCollection();
		$this->setCategories($categories);
	}

	public function getId()
	{
		return $this->id;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function getDescription()
	{
		return $this->description;
	}

	public function getPrice()
	{
		return $this->price;
	}

	public function getTechDetails()
	{
		return $this->techDetails;
	}

	public function getCategories()
	{
		return $this->category;
	}

	public function getMedia()
	{
		return $this->media;
	}

	public function getCreatedAt()
	{
		return date('Y-m-d', $this->createdAt);
	}

	public function getState()
	{
		return $this->statusAlias[$this->state] ?? null;
	}

	public function setCategories($categories = [])
	{
		foreach ($categories as $category) {
			$this->setCategory($category);
		}
	}

	public function setCategory(StoreCategory $category)
	{
		if (!$this->category->contains($category)) {
			$this->category->add($category);
		}
	}

	public function getMiners()
	{
		return $this->miner;
	}

	public function getAvailableMinersQuantity()
	{
		$result = 0;

		/** @var Miner $miner */
		foreach ($this->getMiners() as $miner) {
			if ($miner->getState() == Miner::STATE_AVAILABLE) {
				$result += 1;
			}
		}

		return $result;
	}

}