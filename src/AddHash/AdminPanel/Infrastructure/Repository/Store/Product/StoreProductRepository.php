<?php

namespace App\AddHash\AdminPanel\Infrastructure\Repository\Store\Product;

use App\AddHash\AdminPanel\Domain\Store\Product\StoreProduct;
use App\AddHash\AdminPanel\Domain\Store\Product\StoreProductRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;

class StoreProductRepository implements StoreProductRepositoryInterface
{
	private $entityManager;

	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	/**
	 * @param StoreProduct $product
	 * @throws \Doctrine\ORM\ORMException
	 * @throws \Doctrine\ORM\OptimisticLockException
	 */
	public function create(StoreProduct $product)
	{
		$this->entityManager->persist($product);
		$this->entityManager->flush($product);
	}

	public function listAllProducts()
	{
		$storeProduct = $this->entityManager->getRepository(StoreProduct::class);

		$res = $storeProduct
			->createQueryBuilder('t')
			->select('t')
			->getQuery()->getResult();

		return $res;
	}
}