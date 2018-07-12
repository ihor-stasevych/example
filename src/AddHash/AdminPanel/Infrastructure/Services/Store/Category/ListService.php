<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Store\Category;

use App\AddHash\AdminPanel\Domain\Store\Category\StoreCategoryRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Store\Category\Services\ListServiceInterface;

class ListService implements ListServiceInterface
{
	protected $storeCategoryRepository;

	public function __construct(StoreCategoryRepositoryInterface $storeCategoryRepository)
	{
		$this->storeCategoryRepository = $storeCategoryRepository;
	}

	public function execute()
	{
		return $this->storeCategoryRepository->list();
	}

	public function getOne($id)
	{
		return $this->storeCategoryRepository->findById($id);
	}
}