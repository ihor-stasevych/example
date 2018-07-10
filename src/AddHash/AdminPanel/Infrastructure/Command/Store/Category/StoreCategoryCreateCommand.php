<?php

namespace App\AddHash\AdminPanel\Infrastructure\Command\Store\Category;

use App\AddHash\AdminPanel\Domain\Store\Category\Command\StoreCategoryCreateCommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

class StoreCategoryCreateCommand implements StoreCategoryCreateCommandInterface
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
	private $position;

	public function __construct($title, $position)
	{
		$this->title = $title;
		$this->position = $position;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function getPosition()
	{
		return $this->position;
	}
}