<?php

namespace App\AddHash\AdminPanel\Infrastructure\Command\Store\Product;

use App\AddHash\AdminPanel\Domain\Store\Product\Command\StoreProductCreateCommandInterface;
use App\AddHash\AdminPanel\Domain\Store\Product\StoreProduct;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class StoreProductCreateCommand implements StoreProductCreateCommandInterface
{
	/**
	 * @var string
	 * @Assert\NotBlank()
	 */
	private $title;

	/**
	 * @var string
	 * @Assert\NotBlank()
	 */
	private $description;

	/**
	 * @var string
	 * @Assert\NotBlank()
	 */
	private $techDetails;

	/**
	 * @var string
	 * @Assert\NotBlank()
	 */
	private $price;

	/**
	 * @var array
	 * @Assert\NotNull()
	 * @Assert\Count(min=1)
	 */
	private $categories;

	private $media;

	private $state;

	public function __construct(Request $request)
	{
		$this->title = $request->get('title');
		$this->description = $request->get('description');
		$this->techDetails = $request->get('techDetails', []);
		$this->categories = $request->get('categories', []);
		$this->state = $request->get('state', StoreProduct::STATE_AVAILABLE);
		$this->price = $request->get('price',0);
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function getDescription()
	{
		return $this->description;
	}

	public function getTechDetails()
	{
		return $this->techDetails;
	}

	public function getCategories()
	{
		return $this->categories;
	}

	public function getMedia()
	{
		return $this->media;
	}

	public function getPrice()
	{
		return $this->price;
	}

	public function getState()
	{
		return $this->state;
	}
}