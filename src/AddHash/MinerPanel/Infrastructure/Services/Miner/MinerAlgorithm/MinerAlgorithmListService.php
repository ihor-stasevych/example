<?php

namespace App\AddHash\MinerPanel\Infrastructure\Services\Miner\MinerAlgorithm;

use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\Services\MinerAlgorithmListServiceInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\Repository\MinerAlgorithmRepositoryInterface;

final class MinerAlgorithmListService implements MinerAlgorithmListServiceInterface
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