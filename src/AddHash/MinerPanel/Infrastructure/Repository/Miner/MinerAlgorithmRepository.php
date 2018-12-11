<?php

namespace App\AddHash\MinerPanel\Infrastructure\Repository\Miner;

use Doctrine\ORM\NonUniqueResultException;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerAlgorithm;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerAlgorithmRepositoryInterface;

class MinerAlgorithmRepository extends AbstractRepository implements MinerAlgorithmRepositoryInterface
{
    /**
     * @param int $id
     * @return MinerAlgorithm|null
     * @throws NonUniqueResultException
     */
    public function get(int $id): ?MinerAlgorithm
    {
        return $this->entityManager
            ->getRepository($this->getEntityName())
            ->createQueryBuilder('a')
            ->select('a')
            ->where('a.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function all(): array
    {
        return $this->entityManager
            ->getRepository($this->getEntityName())
            ->createQueryBuilder('a')
            ->select('a')
            ->getQuery()
            ->getArrayResult();
    }

    protected function getEntityName(): string
    {
        return MinerAlgorithm::class;
    }
}