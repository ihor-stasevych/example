<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Store\Product;

use App\AddHash\AdminPanel\Domain\Store\Product\StoreProductRepositoryInterface;
use App\AddHash\AdminPanel\Infrastructure\Transformers\Store\Product\StoreProductGetTransform;
use App\AddHash\AdminPanel\Domain\Store\Product\Command\StoreProductGetCommandInterface;
use App\AddHash\AdminPanel\Domain\Store\Product\Services\StoreProductGetServiceInterface;

class StoreProductGetService implements StoreProductGetServiceInterface
{
	private $productRepository;

	public function __construct(StoreProductRepositoryInterface $storeProductRepository)
	{
		$this->productRepository = $storeProductRepository;
	}

	public function execute(StoreProductGetCommandInterface $command): array
	{
        $product = $this->productRepository->findById($command->getId());

        return (new StoreProductGetTransform())->transform($product);
	}
}