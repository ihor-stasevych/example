<?php

namespace App\AddHash\MinerPanel\Infrastructure\Services\Miner;

use App\AddHash\MinerPanel\Domain\Miner\Miner;
use App\AddHash\MinerPanel\Domain\Miner\MinerRepositoryInterface;
use App\AddHash\MinerPanel\Domain\Miner\Services\MinerCalcHashRateAverageServiceInterface;

final class MinerCalcHashRateAverageService implements MinerCalcHashRateAverageServiceInterface
{
    private $minerRepository;

    public function __construct(MinerRepositoryInterface $minerRepository)
    {
        $this->minerRepository = $minerRepository;
    }

    public function execute(): void
    {
        $date = (new \DateTime())->modify('-1 hour');

        $items = $this->minerRepository->getAvgHashRatesMiners($date);
        $miners = [];

        foreach ($items as $item) {
            /** @var Miner $miner */
            $miner = $item['miner'];

            $miner->setHashRate($item['hashRateAvg']);
            $miners[] = $miner;
        }

        $this->minerRepository->saveAll($miners);
    }

}