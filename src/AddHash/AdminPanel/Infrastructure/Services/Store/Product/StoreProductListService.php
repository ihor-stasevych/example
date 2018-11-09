<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Store\Product;

use App\AddHash\AdminPanel\Domain\Store\Product\ListParam\Sort;
use App\AddHash\AdminPanel\Domain\Store\Product\StoreProductRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Store\Product\Command\StoreProductListCommandInterface;
use App\AddHash\AdminPanel\Domain\Store\Product\Services\StoreProductListServiceInterface;
use App\AddHash\AdminPanel\Infrastructure\Transformers\Store\Product\StoreProductListTransform;

class StoreProductListService implements StoreProductListServiceInterface
{
	private $productRepository;

	public function __construct(StoreProductRepositoryInterface $storeProductRepository)
	{
		$this->productRepository = $storeProductRepository;
	}

	public function execute(StoreProductListCommandInterface $command): array
	{
        $sort = new Sort(
            $command->getSort(),
            $command->getOrder()
        );

        $data = [];
		$products = $this->productRepository->listAllProducts($sort);
        $storeProductListTransform = new StoreProductListTransform();

		foreach ($products as $product) {
            $data[] = $storeProductListTransform->transform($product);
        }

        return $data;
	}
}