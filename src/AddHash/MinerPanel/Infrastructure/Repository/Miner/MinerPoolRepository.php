<?php

namespace App\AddHash\MinerPanel\Infrastructure\Repository\Miner;

use App\AddHash\MinerPanel\Domain\Miner\Miner;
use App\AddHash\MinerPanel\Domain\Miner\MinerPool\MinerPool;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;
use App\AddHash\MinerPanel\Domain\Miner\MinerPool\MinerPoolRepositoryInterface;

class MinerPoolRepository extends AbstractRepository implements MinerPoolRepositoryInterface
{
    public function saveAll(array $minerPools): void
    {
        if (count($minerPools) > 0) {
            foreach ($minerPools as $minerPool) {
                $this->entityManager->persist($minerPool);
            }

            $this->entityManager->flush();
        }
    }

    public function deleteByMiner(Miner $miner): void
    {
//        $this->entityManager->remove($miner);
//        $this->entityManager->flush();
    }

    protected function getEntityName(): string
    {
        return MinerPool::class;
    }
}