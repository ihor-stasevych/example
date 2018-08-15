<?php

namespace App\AddHash\AdminPanel\Domain\Store\Category\Model;

class StoreCategory
{
	private $id;

	private $title;

	private $createdAt;

	private $position;


	public function __construct($title, $position)
	{
		$this->title = $title;
		$this->position = $position;
		$this->createdAt = new \DateTime();
	}

	public function getId()
	{
		return $this->id;
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