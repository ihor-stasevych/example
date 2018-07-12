<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Store\Category;

use App\AddHash\AdminPanel\Domain\Store\Category\StoreCategoryRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Store\Category\Command\StoreCategoryCreateCommandInterface;
use App\AddHash\AdminPanel\Domain\Store\Category\Model\StoreCategory;
use App\AddHash\AdminPanel\Domain\Store\Category\Services\CreateServiceInterface;

class CreateService implements CreateServiceInterface
{
	private $categoryRepository;

	public function __construct(StoreCategoryRepositoryInterface $categoryRepository)
	{
		$this->categoryRepository = $categoryRepository;
	}

	public function execute(StoreCategoryCreateCommandInterface $categoryCreateCommand)
	{
		$storeCategory = new StoreCategory(
			$categoryCreateCommand->getTitle(),
			$categoryCreateCommand->getPosition()
		);

		$this->categoryRepository->create($storeCategory);

		return $storeCategory;
	}
}