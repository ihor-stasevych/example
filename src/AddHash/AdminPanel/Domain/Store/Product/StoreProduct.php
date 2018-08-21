<?php

namespace App\AddHash\AdminPanel\Domain\Store\Product;

use Doctrine\Common\Collections\Criteria;
use App\AddHash\AdminPanel\Domain\Miners\Miner;
use Doctrine\Common\Collections\ArrayCollection;
use App\AddHash\AdminPanel\Domain\Miners\MinerStock;
use App\AddHash\AdminPanel\Domain\Store\Category\Model\StoreCategory;

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
		self::STATE_AVAILABLE   => 'available',
		self::STATE_UNAVAILABLE => 'unavailable'
	];

	private $category;

	private $media;

	/** @var Miner */
	private $miner;

	private $vote;

	private $userVote;

	public function __construct(
		$title,
		$description,
		$techDetails,
		$price,
		$state,
		$categories,
		$vote = null,
		$id = null
	)
	{
		$this->id = $id;
		$this->title = $title;
		$this->description = $description;
		$this->techDetails = $techDetails;
		$this->price = $price;
		$this->state = $state;
		$this->createdAt = new \DateTime();
		$this->category = new ArrayCollection();
		$this->media = new ArrayCollection();
		$this->setCategories($categories);
		$this->vote = $vote;
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
		return $this->createdAt;
	}

	public function getState(): ?string
	{
		return $this->statusAlias[$this->state] ?? null;
	}

	public function getVote()
	{
		return $this->vote;
	}

	public function setCategories(array $categories = [])
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

	public function setVote($vote)
	{
		$this->vote = $vote;
	}

	public function getMiner() : Miner
	{
		return $this->miner;
	}

	/**
	 * @param int $quantity
	 * @return array
	 */
	public function ensureAvailableMiner(int $quantity = 1)
	{
		return $this->ensureMinersByState(MinerStock::STATE_AVAILABLE, $quantity);
	}

	/**
	 * @param int $quantity
	 * @return array
	 */
	public function ensureReservedMiner(int $quantity = 1)
	{
		return $this->ensureMinersByState(MinerStock::STATE_RESERVED, $quantity);
	}

	public function getAvailableMinersQuantity(): int
	{
		$result = 0;

		/** @var MinerStock $stock */
		foreach ($this->miner->getStock() as $stock) {
			if ($stock->getState() == MinerStock::STATE_AVAILABLE) {
				$result += 1;
			}
		}

		return $result;
	}

	public function getReservedMinersQuantity(): int
	{
		$result = 0;

		/** @var MinerStock $stock */
		foreach ($this->miner->getStock() as $stock) {
			if ($stock->getState() == MinerStock::STATE_RESERVED) {
				$result += 1;
			}
		}

		return $result;
	}

	/**
	 * @return MinerStock
	 */
	public function reserveMiner()
	{

		/** @var MinerStock $stock */
		foreach ($this->miner->getStock() as $stock) {
			if ($stock->getState() == MinerStock::STATE_AVAILABLE) {
				$stock->reserveMiner();

				return $stock;
			}
		}
	}

	/**
	 * @return MinerStock
	 */
	public function unReserveMiner()
	{

		/** @var MinerStock $stock */
		foreach ($this->miner->getStock() as $stock) {
			if ($stock->getState() == MinerStock::STATE_RESERVED) {
				$stock->setAvailable();

				return $stock;
			}
		}
	}

	public function setUnavailable()
	{
		$this->state = self::STATE_UNAVAILABLE;
	}

	protected function ensureMinersByState($state, $quantity = 1)
	{
		$criteria = Criteria::create()
			->where(Criteria::expr()->eq('state', $state))
			->orderBy(['priority' => 'ASC'])
			->setFirstResult(0)
			->setMaxResults($quantity);

		return $this->miner->getStock()->matching($criteria)->toArray();
	}
}