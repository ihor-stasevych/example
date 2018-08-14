<?php

namespace App\AddHash\AdminPanel\Domain\Store\Product;

use Doctrine\Common\Collections\Criteria;
use App\AddHash\AdminPanel\Domain\Miners\Miner;
use Doctrine\Common\Collections\ArrayCollection;
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
		$this->miner = new ArrayCollection();
		$this->setCategories($categories);
		$this->vote = $vote;
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getTitle(): string
	{
		return $this->title;
	}

	public function getDescription()
	{
		return $this->description;
	}

	public function getPrice(): float
	{
		return $this->price;
	}

	public function getTechDetails(): string
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

	public function getCreatedAt(): \DateTime
	{
		return $this->createdAt;
	}

	/**
	 * @return mixed|null
	 */
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
	public function ensureAvailableMiner(int $quantity = 1)
	{
		return $this->ensureMinersByState(Miner::STATE_AVAILABLE, $quantity);
	}

	/**
	 * @param $quantity
	 * @return ArrayCollection|\Doctrine\Common\Collections\Collection
	 */
	public function ensureReservedMiner(int $quantity = 1)
	{
		return $this->ensureMinersByState(Miner::STATE_RESERVED, $quantity);
	}

	public function getAvailableMinersQuantity(): int
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

	public function getReservedMinersQuantity(): int
	{
		$result = 0;

		/** @var Miner $miner */
		foreach ($this->getMiners() as $miner) {
			if ($miner->getState() == Miner::STATE_RESERVED) {
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
	 * @return Miner
	 */
	public function unReserveMiner()
	{
		/** @var Miner $miner */
		foreach ($this->getMiners() as $miner) {
			if ($miner->getState() == Miner::STATE_RESERVED) {
				$miner->setAvailable();
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

	protected function ensureMinersByState($state, $quantity = 1)
	{
		$criteria = Criteria::create()
			->where(Criteria::expr()->eq('state', $state))
			->orderBy(['priority' => 'ASC'])
			->setFirstResult(0)
			->setMaxResults($quantity);

		return $this->miner->matching($criteria)->toArray();
	}
}