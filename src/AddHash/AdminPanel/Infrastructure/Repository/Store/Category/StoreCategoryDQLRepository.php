<?php

namespace App\AddHash\AdminPanel\Infrastructure\Repository\Store\Category;

use App\AddHash\AdminPanel\Domain\Store\Category\CategoryRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Store\Category\Model\Store_Category;
use Doctrine\ORM\EntityManagerInterface;

class StoreCategoryDQLRepository implements CategoryRepositoryInterface
{
	protected $entityManager;

	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	public function findById($id): ?Store_Category
	{
		$this->entityManager->getRepository(Store_Category::class)->find($id);
	}

	public function create(Store_Category $category)
	{
		// TODO: Implement create() method.
	}

	public function update(Store_Category $category)
	{
		// TODO: Implement update() method.
	}
}