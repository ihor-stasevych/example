<?php

namespace App\AddHash\MinerPanel\Infrastructure\Services\Miner\MinerAlgorithm;

use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerAlgorithmRepositoryInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\Services\MinerAlgorithmAllServiceInterface;

final class MinerAlgorithmAllService implements MinerAlgorithmAllServiceInterface
{
    private $minerAlgorithmRepository;

    public function __construct(MinerAlgorithmRepositoryInterface $minerAlgorithmRepository)
    {
        $this->minerAlgorithmRepository = $minerAlgorithmRepository;
    }

    public function execute(): array
    {
        return $this->minerAlgorithmRepository->all();
    }
}