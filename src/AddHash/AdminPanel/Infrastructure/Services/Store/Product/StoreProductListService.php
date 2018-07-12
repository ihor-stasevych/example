<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Store\Product;

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

	public function execute()
	{
		return $this->productRepository->listAllProducts();
	}

	/**
	 * Make Command!
	 * @param Request $request
	 */
	public function search(Request $request)
	{

	}
}