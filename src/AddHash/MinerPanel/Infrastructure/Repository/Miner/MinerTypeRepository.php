<?php

namespace App\AddHash\MinerPanel\Infrastructure\Repository\Miner;

use App\AddHash\MinerPanel\Domain\Miner\MinerType\MinerType;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;
use App\AddHash\MinerPanel\Domain\Miner\MinerType\MinerTypeRepositoryInterface;

class MinerTypeRepository extends AbstractRepository implements MinerTypeRepositoryInterface
{
    public function get(int $id): ?MinerType
    {
        /** @var MinerType $minerType */
        $minerType = $this->entityRepository->find($id);

        return $minerType;
    }

    public function all(): array
    {
        return $this->entityRepository->findAll();
    }

    protected function getEntityName(): string
    {
        return MinerType::class;
    }
}