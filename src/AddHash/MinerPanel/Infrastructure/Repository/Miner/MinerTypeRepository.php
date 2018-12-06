<?php

namespace App\AddHash\MinerPanel\Infrastructure\Repository\Miner;

use App\AddHash\MinerPanel\Domain\Miner\MinerType\Model\MinerType;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;
use App\AddHash\MinerPanel\Domain\Miner\MinerType\Repository\MinerTypeRepositoryInterface;

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