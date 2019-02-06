<?php

namespace App\AddHash\MinerPanel\Infrastructure\Repository\Miner;

use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use App\AddHash\MinerPanel\Domain\User\User;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;
use App\AddHash\MinerPanel\Domain\Miner\MinerHashRate\MinerHashRate;
use App\AddHash\MinerPanel\Domain\Miner\MinerHashRate\MinerHashRateRepositoryInterface;

class MinerHashRateRepository extends AbstractRepository implements MinerHashRateRepositoryInterface
{
    public function getAverageValuesByUser(User $user): array
    {
        return $this->entityManager
            ->getRepository($this->getEntityName())
            ->createQueryBuilder('h')
            ->select('avg(h.value) as hashRate', 'h')
            ->where('h.user = :user')
            ->groupBy('h.algorithm')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param array $hashRates
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function saveAll(array $hashRates): void
    {
        if (count($hashRates) > 0) {
            foreach ($hashRates as $hashRate) {
                $this->entityManager->persist($hashRate);
            }

            $this->entityManager->flush();
        }
    }

    protected function getEntityName(): string
    {
        return MinerHashRate::class;
    }
}