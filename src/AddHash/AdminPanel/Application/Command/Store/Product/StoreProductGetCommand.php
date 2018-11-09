<?php

namespace App\AddHash\AdminPanel\Application\Command\Store\Product;

use App\AddHash\AdminPanel\Domain\Store\Product\Command\StoreProductGetCommandInterface;

final class StoreProductGetCommand implements StoreProductGetCommandInterface
{
	private $id;

	public function __construct($id)
	{
		$this->id = $id;
	}

	public function getId(): int
    {
        return $this->id;
    }
}