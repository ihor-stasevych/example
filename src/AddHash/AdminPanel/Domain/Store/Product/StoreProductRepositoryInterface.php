<?php

namespace App\AddHash\AdminPanel\Domain\Store\Product;

use Symfony\Component\HttpFoundation\Request;
use App\AddHash\AdminPanel\Domain\Store\Product\ListParam\Sort;

interface StoreProductRepositoryInterface
{
	public function create(StoreProduct $product);

	public function save(StoreProduct $product);

	public function listAllProducts(Sort $sort);

	public function searchProducts(Request $request);

	public function findById($id);

	public function findByIds(array $ids);
}