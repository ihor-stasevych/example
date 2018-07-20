<?php

namespace App\AddHash\AdminPanel\Infrastructure\Repository\Store\Product;

use App\AddHash\AdminPanel\Domain\Store\Product\StoreProduct;
use App\AddHash\AdminPanel\Domain\Store\Product\StoreProductRepositoryInterface;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;
use Symfony\Component\HttpFoundation\Request;

class StoreProductRepository extends AbstractRepository implements StoreProductRepositoryInterface
{
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
		$res = $this->entityRepository
			->createQueryBuilder('t')
			->select('t', 'm')
			->join('t.miner', 'm')
			->where('t.state = :state')
			#->andWhere('m.state = :minerState')
			->setParameter('state', StoreProduct::STATE_AVAILABLE)
			#->setParameter('minerState', Miner::STATE_AVAILABLE)
			#->groupBy('t.id')
			->getQuery()
			->getResult();

		return $res;
	}

	/**
	 * TODO: Write to command instead of Request!
	 * @param Request $request
	 * @return mixed
	 */
	public function searchProducts(Request $request)
	{
		$qb = $this->entityRepository
			->createQueryBuilder('a')
			->where('a.title LIKE :query')
			->orWhere('a.description LIKE :query')
			->setParameter('query', "% {$request->get('query')} %");

		return $qb->getQuery()->getResult();
	}

	public function findById($id)
	{
		return $this->entityRepository->find($id);
	}

	public function findByIds(array $ids)
	{
		$storeProducts = $this->entityManager->getRepository($this->getEntityName());

		$res = $storeProducts->createQueryBuilder('e')
			->select('e')
			->where('e.id in (:ids)')
			->setParameter('ids', $ids)
			->getQuery()
			->getResult();


		return $res;
	}

	/**
	 * @return string
	 */
	protected function getEntityName()
	{
		return StoreProduct::class;
	}
}