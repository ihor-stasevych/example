<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Store\Product;


use App\AddHash\AdminPanel\Domain\Store\Category\StoreCategoryRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Store\Product\Command\StoreProductCreateCommandInterface;
use App\AddHash\AdminPanel\Domain\Store\Product\Services\StoreProductCreateServiceInterface;
use App\AddHash\AdminPanel\Domain\Store\Product\StoreProduct;
use App\AddHash\AdminPanel\Domain\Store\Product\StoreProductRepositoryInterface;
use App\AddHash\AdminPanel\Infrastructure\Repository\Store\Category\StoreCategoryRepository;

class StoreProductCreateService implements StoreProductCreateServiceInterface
{

	private $productRepository;
	private $categoryRepository;

	public function __construct(
		StoreProductRepositoryInterface $productRepository,
		StoreCategoryRepositoryInterface $categoryRepository
	)
	{
		$this->productRepository = $productRepository;
		$this->categoryRepository = $categoryRepository;
	}

	public function execute(StoreProductCreateCommandInterface $command)
	{
		$categories = $this->categoryRepository->findByIds($command->getCategories());

		$product = new StoreProduct(
			$command->getTitle(),
			$command->getDescription(),
			$command->getTechDetails(),
			$command->getPrice(),
			$command->getState(),
			$categories
		);

		$this->productRepository->create($product);
	}
}