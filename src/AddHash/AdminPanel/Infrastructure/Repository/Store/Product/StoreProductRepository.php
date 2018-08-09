<?php

namespace App\AddHash\AdminPanel\Infrastructure\Repository\Store\Product;

use Doctrine\Common\Collections\Criteria;
use Symfony\Component\HttpFoundation\Request;
use App\AddHash\AdminPanel\Domain\Store\Product\StoreProduct;
use App\AddHash\AdminPanel\Domain\Store\Product\ListParam\Sort;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;
use App\AddHash\AdminPanel\Domain\Store\Product\StoreProductRepositoryInterface;

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

	/**
	 * @param StoreProduct $product
	 * @throws \Doctrine\ORM\ORMException
	 * @throws \Doctrine\ORM\OptimisticLockException
	 */
	public function save(StoreProduct $product)
	{
		$this->entityManager->flush($product);
	}

    /**
     * @param Sort $sort
     * @return Criteria
     */
    public function createSortCriteria(Sort $sort)
    {
        return Criteria::create()->orderBy([
            $sort->getSort() => $sort->getOrder()
        ]);
    }

    /**
     * @param Sort $sort
     * @return mixed
     */
	public function listAllProducts(Sort $sort)
	{
		$res = $this->entityRepository
			->createQueryBuilder('t')
			->select('t', 'm')
			->join('t.miner', 'm')
			->where('t.state = :state')
			->setParameter('state', StoreProduct::STATE_AVAILABLE)
            ->orderBy('t.' . $sort->getSort(), $sort->getOrder())
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