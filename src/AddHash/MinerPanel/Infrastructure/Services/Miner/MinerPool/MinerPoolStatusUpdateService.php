<?php

namespace App\AddHash\MinerPanel\Infrastructure\Services\Miner\MinerPool;

use App\AddHash\MinerPanel\Domain\Miner\Miner;
use App\AddHash\MinerPanel\Domain\Miner\MinerRepositoryInterface;
use App\AddHash\MinerPanel\Infrastructure\Miner\Extender\MinerSocket;
use App\AddHash\MinerPanel\Infrastructure\Miner\Parsers\MinerStatusParser;
use App\AddHash\MinerPanel\Infrastructure\Miner\ApiCommand\MinerApiCommand;
use App\AddHash\MinerPanel\Domain\Miner\MinerPool\Services\MinerPoolStatusUpdateServiceInterface;

final class MinerPoolStatusUpdateService implements MinerPoolStatusUpdateServiceInterface
{
    private $minerRepository;

    public function __construct(MinerRepositoryInterface $minerRepository)
    {
        $this->minerRepository = $minerRepository;
    }

    public function execute(): void
    {
        $miners = $this->minerRepository->getMinerByStatusPool(Miner::STATUS_POOL_OFF);

        if (false === empty($miners)) {
            $minersWithStatusOn = [];

            /** @var Miner $miner */
            foreach ($miners as $miner) {
                $minerApiCommand = new MinerApiCommand(
                    new MinerSocket($miner->getCredential()),
                    new MinerStatusParser()
                );

                $status = $minerApiCommand->getSummary();
                $status = $status['status'];

                if ($status === MinerStatusParser::STATUS_ON) {
                    $miner->setStatusPoolOn();
                    $minersWithStatusOn[] = $miner;
                }
            }

            if (false === empty($minersWithStatusOn)) {
                $this->minerRepository->saveAll($minersWithStatusOn);
            }
        }
    }
}