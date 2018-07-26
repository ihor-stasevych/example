<?php

namespace App\AddHash\AdminPanel\Domain\Store\Product;

use Symfony\Component\HttpFoundation\Request;

interface StoreProductRepositoryInterface
{
	public function create(StoreProduct $product);

	public function save(StoreProduct $product);

	public function listAllProducts();

	public function searchProducts(Request $request);

	public function findById($id);

	public function findByIds(array $ids);
}