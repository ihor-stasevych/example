<?php

namespace App\AddHash\MinerPanel\Infrastructure\Services\Miner;

use App\AddHash\MinerPanel\Domain\Miner\Miner;
use App\AddHash\MinerPanel\Domain\Miner\MinerRepositoryInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerHashRate\MinerHashRate;
use App\AddHash\MinerPanel\Infrastructure\Miner\Extender\MinerSocket;
use App\AddHash\MinerPanel\Infrastructure\Miner\Parsers\MinerSummaryParser;
use App\AddHash\MinerPanel\Infrastructure\Miner\ApiCommand\MinerApiCommand;
use App\AddHash\MinerPanel\Domain\Miner\Services\MinerInfoGetServiceInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerHashRate\MinerHashRateRepositoryInterface;

final class MinerInfoGetService implements MinerInfoGetServiceInterface
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
        $miners = $this->minerRepository->getAll();
        $hashRateActiveMiners = [];

        /** @var Miner $miner */
        foreach ($miners as $miner) {
            $minerApiCommand = new MinerApiCommand(
                new MinerSocket($miner->getCredential()),
                new MinerSummaryParser()
            );

            $summary = $minerApiCommand->getSummary();
            $miner->setStatusNoActive();

            if (false === empty($summary['status'])) {
                $miner->setStatusActive();

                if (false === empty($summary['hashRateAverage'])) {
                    $hashRateActiveMiners[] = new MinerHashRate($summary['hashRateAverage'], $miner);
                }
            }
        }

        $this->minerRepository->saveAll($miners);
        $this->minerHashRateRepository->saveAll($hashRateActiveMiners);
    }
}