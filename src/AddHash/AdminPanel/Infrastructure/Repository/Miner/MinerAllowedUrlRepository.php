<?php

namespace App\AddHash\AdminPanel\Infrastructure\Repository\Miner;

use Doctrine\ORM\NonUniqueResultException;
use App\AddHash\AdminPanel\Domain\Miners\MinerAllowedUrl;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;
use App\AddHash\AdminPanel\Domain\Miners\Repository\MinerAllowedUrlRepositoryInterface;

class MinerAllowedUrlRepository extends AbstractRepository implements MinerAllowedUrlRepositoryInterface
{
    /**
     * @param array $values
     * @return int
     * @throws NonUniqueResultException
     */
    public function getCountByValuesEnabledUrl(array $values): int
    {
        return $this->entityManager
            ->getRepository($this->getEntityName())
            ->createQueryBuilder('a')
            ->select('count(a.id)')
            ->andWhere('a.value IN (:values)')
            ->andWhere('a.status = :status')
            ->setParameter('values', $values)
            ->setParameter('status', MinerAllowedUrl::ENABLE)
            ->getQuery()
            ->getSingleScalarResult();
    }

	/**
	 * @return string
	 */
	protected function getEntityName()
	{
		return MinerAllowedUrl::class;
	}
}