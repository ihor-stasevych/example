<?php

namespace App\AddHash\MinerPanel\Infrastructure\Repository\Miner;

use App\AddHash\System\GlobalContext\Repository\AbstractRepository;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerAlgorithm;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerAlgorithmRepositoryInterface;

class MinerAlgorithmRepository extends AbstractRepository implements MinerAlgorithmRepositoryInterface
{
    public function get(int $id): ?MinerAlgorithm
    {
        /** @var MinerAlgorithm $minerAlgorithm */
        $minerAlgorithm = $this->entityRepository->find($id);

        return $minerAlgorithm;
    }

    public function all(): array
    {
        return $this->entityRepository->findAll();
    }

    protected function getEntityName(): string
    {
        return MinerAlgorithm::class;
    }
}