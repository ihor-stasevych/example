<?php

namespace App\AddHash\AdminPanel\Domain\Store\Category;

use App\AddHash\AdminPanel\Domain\Store\Category\Model\Store_Category;

interface CategoryRepositoryInterface
{
	public function findById($id): ?Store_Category;

	public function create(Store_Category $category);

	public function update(Store_Category $category);
}