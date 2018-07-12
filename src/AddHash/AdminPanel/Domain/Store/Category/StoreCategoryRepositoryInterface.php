<?php

namespace App\AddHash\AdminPanel\Domain\Store\Category;

use App\AddHash\AdminPanel\Domain\Store\Category\Model\StoreCategory;

interface StoreCategoryRepositoryInterface
{
	public function findById($id): ?StoreCategory;

	public function create(StoreCategory $category);

	public function update(StoreCategory $category);

	public function list();

	public function findByIds(array $ids);
}