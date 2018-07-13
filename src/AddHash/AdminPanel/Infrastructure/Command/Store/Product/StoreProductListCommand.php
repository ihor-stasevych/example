<?php

namespace App\AddHash\AdminPanel\Infrastructure\Command\Store\Product;


use App\AddHash\AdminPanel\Domain\Store\Product\Command\StoreProductListCommandInterface;

class StoreProductListCommand implements StoreProductListCommandInterface
{
	private $id;

	private $title;

	public function __construct($id = null, $title = '')
	{
		$this->id = $id;
		$this->title = $title;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getTitle()
	{
		return $this->title;
	}
}