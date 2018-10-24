<?php

namespace App\AddHash\AdminPanel\Application\Command\Store\Product;

use App\AddHash\AdminPanel\Domain\Store\Product\Command\StoreProductListCommandInterface;

final class StoreProductListCommand implements StoreProductListCommandInterface
{
	private $sort;

	private $order;

	public function __construct($sort = null, $order = null)
	{
		$this->sort = $sort;
		$this->order = $order;
	}

	public function getSort(): ?string
	{
		return $this->sort;
	}

	public function getOrder(): ?string
    {
        return $this->order;
    }
}