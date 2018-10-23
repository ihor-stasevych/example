<?php

namespace App\AddHash\AdminPanel\Infrastructure\Repository\Miner;

use App\AddHash\AdminPanel\Domain\Miners\MinerAllowedUrl;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;
use App\AddHash\AdminPanel\Domain\Miners\Repository\MinerAllowedUrlRepositoryInterface;

class MinerAllowedUrlRepository extends AbstractRepository implements MinerAllowedUrlRepositoryInterface
{
    public function getByValuesEnabledUrl(array $values): array
    {
        return $this->entityManager
            ->getRepository($this->getEntityName())
            ->createQueryBuilder('a')
            ->select('a')
            ->andWhere('a.value IN (:values)')
            ->andWhere('a.status = :status')
            ->setParameter('values', $values)
            ->setParameter('status', MinerAllowedUrl::ENABLE)
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