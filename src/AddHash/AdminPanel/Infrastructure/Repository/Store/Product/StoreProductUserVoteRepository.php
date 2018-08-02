<?php

namespace App\AddHash\AdminPanel\Infrastructure\Repository\Store\Product;

use App\AddHash\System\GlobalContext\Repository\AbstractRepository;
use App\AddHash\AdminPanel\Domain\Store\Product\StoreProductUserVote;
use App\AddHash\AdminPanel\Domain\Store\Product\StoreProductUserVoteRepositoryInterface;

class StoreProductUserVoteRepository extends AbstractRepository implements StoreProductUserVoteRepositoryInterface
{
	public function create(StoreProductUserVote $vote)
	{
		$this->entityManager->persist($vote);
		$this->entityManager->flush($vote);
	}

	public function getByUserIdAndProductId(int $userId, int $productId): ?StoreProductUserVote
    {
        $userVote = $this->entityManager->getRepository($this->getEntityName());

        $res = $userVote->createQueryBuilder('uv')
            ->select('uv')
            ->andWhere('uv.product = :productId')
            ->andWhere('uv.user = :userId')
            ->setParameter('productId', $productId)
            ->setParameter('userId', $userId)
            ->getQuery();

        return $res->getOneOrNullResult();
    }

    public function getAvgByProductId(int $productId)
    {
        $userVote = $this->entityManager->getRepository($this->getEntityName());

        $res = $userVote->createQueryBuilder('uv')
            ->select('avg(uv.value) as avg')
            ->andWhere('uv.product = :productId')
            ->setParameter('productId', $productId)
            ->getQuery();

        return $res->getOneOrNullResult();
    }

	/**
	 * @return string
	 */
	protected function getEntityName()
	{
		return StoreProductUserVote::class;
	}
}