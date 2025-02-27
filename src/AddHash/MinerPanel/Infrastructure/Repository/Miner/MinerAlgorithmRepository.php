<?php

namespace App\AddHash\MinerPanel\Infrastructure\Repository\Miner;

use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
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

    /**
     * @param string $value
     * @return MinerAlgorithm|null
     * @throws NonUniqueResultException
     */
    public function getByValue(string $value): ?MinerAlgorithm
    {
        return $this->entityManager
            ->getRepository($this->getEntityName())
            ->createQueryBuilder('a')
            ->select('a')
            ->where('a.value = :value')
            ->setParameter('value', $value)
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

    /**
     * @param MinerAlgorithm $algorithm
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(MinerAlgorithm $algorithm): void
    {
        $this->entityManager->persist($algorithm);
        $this->entityManager->flush();
    }

    protected function getEntityName(): string
    {
        return MinerAlgorithm::class;
    }
}