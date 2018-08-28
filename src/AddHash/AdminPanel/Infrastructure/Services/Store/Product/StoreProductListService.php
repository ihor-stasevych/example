<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Store\Product;

use App\AddHash\AdminPanel\Domain\Store\Product\ListParam\Sort;
use App\AddHash\AdminPanel\Domain\Store\Product\StoreProductRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Store\Product\Command\StoreProductListCommandInterface;
use App\AddHash\AdminPanel\Domain\Store\Product\Services\StoreProductListServiceInterface;
use App\AddHash\System\Lib\Cache\CacheInterface;

class StoreProductListService implements StoreProductListServiceInterface
{
	const PRODUCTS_KEY = 'listProducts';
	private $productRepository;
	private $cache;

	public function __construct(StoreProductRepositoryInterface $storeProductRepository, CacheInterface $cache)
	{
		$this->productRepository = $storeProductRepository;
		$this->cache = $cache;
	}

	public function execute(StoreProductListCommandInterface $command)
	{
        $sort = new Sort(
            $command->getSort(),
            $command->getOrder()
        );

        #$res = $this->productRepository->listAllProducts($sort);

        #$this->cache->add('listProducts', $res);

		return $this->productRepository->listAllProducts($sort);
	}
}