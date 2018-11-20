<?php

namespace App\AddHash\AdminPanel\Infrastructure\Repository\Miner;

use App\AddHash\AdminPanel\Domain\Miners\MinerAlgorithm;
use App\AddHash\AdminPanel\Domain\Miners\MinerAllowedUrl;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;
use App\AddHash\AdminPanel\Domain\Miners\Repository\MinerAllowedUrlRepositoryInterface;

class MinerAllowedUrlRepository extends AbstractRepository implements MinerAllowedUrlRepositoryInterface
{
    public function getByValuesAndAlgorithm(MinerAlgorithm $algorithm, array $values): array
    {
        return $this->entityManager
            ->getRepository($this->getEntityName())
            ->createQueryBuilder('a')
            ->select('a')
            ->where('a.value IN (:values)')
            ->andWhere('a.status = :status')
            ->andWhere('a.algorithm = :algorithm')
            ->setParameter('values', $values)
            ->setParameter('status', MinerAllowedUrl::ENABLE)
            ->setParameter('algorithm', $algorithm)
            ->getQuery()
            ->getResult();
    }

	/**
	 * @return string
	 */
	protected function getEntityName()
	{
		return MinerAllowedUrl::class;
	}
}