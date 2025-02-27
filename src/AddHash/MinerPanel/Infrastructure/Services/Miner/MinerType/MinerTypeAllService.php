<?php

namespace App\AddHash\MinerPanel\Infrastructure\Services\Miner\MinerType;

use App\AddHash\MinerPanel\Domain\Miner\MinerType\MinerTypeRepositoryInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerType\Services\MinerTypeAllServiceInterface;

final class MinerTypeAllService implements MinerTypeAllServiceInterface
{
    private $minerTypeRepository;

    public function __construct(MinerTypeRepositoryInterface $minerTypeRepository)
    {
        $this->minerTypeRepository = $minerTypeRepository;
    }

    public function execute(): array
    {
        return $this->minerTypeRepository->all();
    }
}