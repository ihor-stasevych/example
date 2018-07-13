<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Store\Product;

use App\AddHash\AdminPanel\Domain\Store\Product\Command\StoreProductListCommandInterface;
use App\AddHash\AdminPanel\Domain\Store\Product\Services\StoreProductListServiceInterface;
use App\AddHash\AdminPanel\Domain\Store\Product\StoreProductRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;

class StoreProductListService implements StoreProductListServiceInterface
{
	private $productRepository;

	public function __construct(StoreProductRepositoryInterface $storeProductRepository)
	{
		$this->productRepository = $storeProductRepository;
	}

	public function execute(StoreProductListCommandInterface $command)
	{
		if (!$command->getId()) {
			$result = $this->productRepository->listAllProducts();
		} else {
			$result = $this->productRepository->findById($command->getId());
		}

		return $result;
	}
}