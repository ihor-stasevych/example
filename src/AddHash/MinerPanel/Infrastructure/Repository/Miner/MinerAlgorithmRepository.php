<?php

namespace App\AddHash\MinerPanel\Infrastructure\Repository\Miner;

use App\AddHash\MinerPanel\Domain\Miner\Model\MinerAlgorithm;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;
use App\AddHash\MinerPanel\Domain\Miner\Repository\MinerAlgorithmRepositoryInterface;

class MinerAlgorithmRepository extends AbstractRepository implements MinerAlgorithmRepositoryInterface
{
    public function get(int $id): ?MinerAlgorithm
    {
        /** @var MinerAlgorithm $minerAlgorithm */
        $minerAlgorithm = $this->entityRepository->find($id);

        return $minerAlgorithm;
    }

    protected function getEntityName(): string
    {
        return MinerAlgorithm::class;
    }
}