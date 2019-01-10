<?php

namespace App\AddHash\MinerPanel\Infrastructure\Repository\Miner;

use Doctrine\ORM\NonUniqueResultException;
use App\AddHash\MinerPanel\Domain\Miner\MinerType\MinerType;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;
use App\AddHash\MinerPanel\Domain\Miner\MinerType\MinerTypeRepositoryInterface;

class MinerTypeRepository extends AbstractRepository implements MinerTypeRepositoryInterface
{
    /**
     * @param int $id
     * @return MinerType|null
     * @throws NonUniqueResultException
     */
    public function get(int $id): ?MinerType
    {
        return $this->entityManager
            ->getRepository($this->getEntityName())
            ->createQueryBuilder('t')
            ->select('t')
            ->where('t.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function all(): array
    {
        return $this->entityManager
            ->getRepository($this->getEntityName())
            ->createQueryBuilder('t')
            ->select('t')
            ->getQuery()
            ->getArrayResult();
    }

    protected function getEntityName(): string
    {
        return MinerType::class;
    }
}