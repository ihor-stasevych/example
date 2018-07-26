<?php

namespace App\AddHash\AdminPanel\Domain\Store\Product;


use App\AddHash\AdminPanel\Domain\Miners\Miner;
use App\AddHash\AdminPanel\Domain\Store\Category\Model\StoreCategory;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

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

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return mixed
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @return mixed
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @return mixed
	 */
	public function getPrice()
	{
		return $this->price;
	}

	/**
	 * @return mixed
	 */
	public function getTechDetails()
	{
		return $this->techDetails;
	}

	/**
	 * @return ArrayCollection
	 */
	public function getCategories()
	{
		return $this->category;
	}

	/**
	 * @return ArrayCollection
	 */
	public function getMedia()
	{
		return $this->media;
	}

	/**
	 * @return false|string
	 */
	public function getCreatedAt()
	{
		return date('Y-m-d', $this->createdAt);
	}

	/**
	 * @return mixed|null
	 */
	public function getState()
	{
		return $this->statusAlias[$this->state] ?? null;
	}

	/**
	 * @param array $categories
	 */
	public function setCategories($categories = [])
	{
		foreach ($categories as $category) {
			$this->setCategory($category);
		}
	}

	/**
	 * @param StoreCategory $category
	 */
	public function setCategory(StoreCategory $category)
	{
		if (!$this->category->contains($category)) {
			$this->category->add($category);
		}
	}

	/**
	 * @return ArrayCollection
	 */
	public function getMiners()
	{
		return $this->miner;
	}

	/**
	 * @param $quantity
	 * @return ArrayCollection|\Doctrine\Common\Collections\Collection
	 */
	public function ensureAvailableMiner($quantity)
	{
		$criteria = Criteria::create()
			->where(Criteria::expr()->eq('state', Miner::STATE_AVAILABLE))
			->orderBy(['priority' => 'ASC'])
			->setFirstResult(0)
			->setMaxResults($quantity);

		return $this->miner->matching($criteria);
	}

	/**
	 * @return int
	 */
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

	/**
	 * @return Miner
	 */
	public function reserveMiner()
	{
		/** @var Miner $miner */
		foreach ($this->getMiners() as $miner) {
			if ($miner->getState() == Miner::STATE_AVAILABLE) {
				$miner->reserveMiner();
				return $miner;
			}
		}
	}

	/**
	 * @inheritdoc
	 */
	public function setUnavailable()
	{
		$this->state = self::STATE_UNAVAILABLE;
	}

}