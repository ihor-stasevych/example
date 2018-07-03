<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Store\Category;

use App\AddHash\AdminPanel\Domain\Store\Category\CategoryRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Store\Category\Services\ListServiceInterface;

class ListService implements ListServiceInterface
{
	protected $storeCategoryRepository;

	public function __construct(CategoryRepositoryInterface $storeCategoryRepository)
	{
		$this->storeCategoryRepository = $storeCategoryRepository;
	}

	public function execute()
	{
		return $this->storeCategoryRepository->list();
	}
}