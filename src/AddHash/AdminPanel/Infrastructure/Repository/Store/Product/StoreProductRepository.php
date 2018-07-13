<?php

namespace App\AddHash\AdminPanel\Infrastructure\Repository\Store\Product;

use App\AddHash\AdminPanel\Domain\Store\Product\StoreProduct;
use App\AddHash\AdminPanel\Domain\Store\Product\StoreProductRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

class StoreProductRepository implements StoreProductRepositoryInterface
{
	private $entityManager;
	private $productRepository;

	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
		$this->productRepository = $this->entityManager->getRepository(StoreProduct::class);
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
		$res = $this->productRepository
			->createQueryBuilder('t')
			->select('t')
			->getQuery()->getResult();

		return $res;
	}

	/**
	 * TODO: Write to command instead of Request!
	 * @param Request $request
	 * @return mixed
	 */
	public function searchProducts(Request $request)
	{
		$qb = $this->productRepository
			->createQueryBuilder('a')
			->where('a.title LIKE :query')
			->orWhere('a.description LIKE :query')
			->setParameter('query', "% {$request->get('query')} %");

		return $qb->getQuery()->getResult();
	}

	public function findById($id)
	{
		return $this->productRepository->find($id);
	}
}