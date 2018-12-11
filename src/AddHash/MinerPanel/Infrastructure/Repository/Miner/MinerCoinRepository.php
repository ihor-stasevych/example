<?php

namespace App\AddHash\MinerPanel\Infrastructure\Repository\Miner;

use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\NonUniqueResultException;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin\MinerCoin;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin\MinerCoinRepositoryInterface;

class MinerCoinRepository extends AbstractRepository implements MinerCoinRepositoryInterface
{
    /**
     * @param string $tag
     * @return MinerCoin|null
     * @throws NonUniqueResultException
     */
    public function getByTag(string $tag): ?MinerCoin
    {
        return $this->entityManager
            ->getRepository($this->getEntityName())
            ->createQueryBuilder('c')
            ->select('c')
            ->where('c.tag = :tag')
            ->setParameter('tag', $tag)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param MinerCoin $coin
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(MinerCoin $coin): void
    {
        $this->entityManager->persist($coin);
        $this->entityManager->flush();
    }

    protected function getEntityName(): string
    {
        return MinerCoin::class;
    }
}