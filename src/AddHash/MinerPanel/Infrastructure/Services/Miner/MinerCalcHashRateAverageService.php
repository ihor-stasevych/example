<?php

namespace App\AddHash\MinerPanel\Infrastructure\Services\Miner;

use App\AddHash\MinerPanel\Domain\Miner\MinerRepositoryInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerHashRate\MinerHashRateRepositoryInterface;
use App\AddHash\MinerPanel\Domain\Miner\Services\MinerCalcHashRateAverageServiceInterface;

final class MinerCalcHashRateAverageService implements MinerCalcHashRateAverageServiceInterface
{
    private $minerRepository;

    private $minerHashRateRepository;

    public function __construct(
        MinerRepositoryInterface $minerRepository,
        MinerHashRateRepositoryInterface $minerHashRateRepository
    )
    {
        $this->minerRepository = $minerRepository;
        $this->minerHashRateRepository = $minerHashRateRepository;
    }

    public function execute(): void
    {

    }
}