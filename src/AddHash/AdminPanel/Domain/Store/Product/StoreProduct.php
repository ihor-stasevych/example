<?php

namespace App\AddHash\AdminPanel\Domain\Store\Product;


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

	/**
	 * @var ArrayCollection
	 */
	private $category;

	public function __construct($title, $description, $techDetails, $price, $state)
	{
		$this->title = $title;
		$this->description = $description;
		$this->techDetails = $techDetails;
		$this->price = $price;
		$this->state = $state;
		$this->createdAt = time();
		$this->category = new ArrayCollection();
	}

	public function getId()
	{
		return $this->id;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function getCategories()
	{
		return $this->category;
	}

	public function setCategory(StoreCategory $category)
	{
		if (!$this->category->contains($category)) {
			$this->category->add($category);
		}
	}

}