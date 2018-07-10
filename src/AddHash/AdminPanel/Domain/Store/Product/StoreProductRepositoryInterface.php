<?php

namespace App\AddHash\AdminPanel\Domain\Store\Product;

interface StoreProductRepositoryInterface
{
	public function create(StoreProduct $product);

	public function listAllProducts();
}