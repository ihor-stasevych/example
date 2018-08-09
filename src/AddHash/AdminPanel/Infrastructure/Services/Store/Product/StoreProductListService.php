<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Store\Product;

use App\AddHash\AdminPanel\Domain\Store\Product\ListParam\Sort;
use App\AddHash\AdminPanel\Domain\Store\Product\StoreProductRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Store\Product\Command\StoreProductListCommandInterface;
use App\AddHash\AdminPanel\Domain\Store\Product\Services\StoreProductListServiceInterface;

class StoreProductListService implements StoreProductListServiceInterface
{
	private $productRepository;

	public function __construct(StoreProductRepositoryInterface $storeProductRepository)
	{
		$this->productRepository = $storeProductRepository;
	}

	public function execute(StoreProductListCommandInterface $command)
	{
        $sort = new Sort(
            $command->getSort(),
            $command->getOrder()
        );

		return $this->productRepository->listAllProducts($sort);


        //$result = $this->productRepository->findById($command->getId());
	}
}