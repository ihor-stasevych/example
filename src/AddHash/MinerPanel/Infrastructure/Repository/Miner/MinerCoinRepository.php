<?php

namespace App\AddHash\MinerPanel\Infrastructure\Repository\Miner;

use Pagerfanta\Pagerfanta;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Pagerfanta\Adapter\DoctrineORMAdapter;
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

    public function getCoinsWithPagination(?int $currentPage): Pagerfanta
    {
        $result = $this->entityManager
            ->getRepository($this->getEntityName())
            ->createQueryBuilder('c')
            ->select('c', 'a')
            ->join('c.algorithm', 'a')
            ->getQuery();

        $pager = new Pagerfanta(
            new DoctrineORMAdapter($result)
        );

        $pager->setMaxPerPage(MinerCoin::MAX_PER_PAGE);
        $pager->setCurrentPage($currentPage ?? 1);

        return $pager;
    }

    protected function getEntityName(): string
    {
        return MinerCoin::class;
    }
}