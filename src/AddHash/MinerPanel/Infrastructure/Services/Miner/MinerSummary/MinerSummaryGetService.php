<?php

namespace App\AddHash\MinerPanel\Infrastructure\Services\Miner\MinerSummary;

use App\AddHash\MinerPanel\Domain\Miner\Summary\SummaryGetHandlerInterface;
use App\AddHash\MinerPanel\Domain\Miner\Repository\MinerRepositoryInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerSummary\MinerSummaryGetServiceInterface;
use App\AddHash\MinerPanel\Domain\User\Services\UserAuthenticationGetServiceInterface;
use App\AddHash\MinerPanel\Domain\Miner\Exceptions\MinerSummaryGetInvalidMinerException;
use App\AddHash\MinerPanel\Domain\Miner\Command\MinerSummary\MinerSummaryGetCommandInterface;

final class MinerSummaryGetService implements MinerSummaryGetServiceInterface
{
    private $authenticationAdapter;

    private $minerRepository;

    private $summaryGetHandler;

    public function __construct(
        UserAuthenticationGetServiceInterface $authenticationAdapter,
        MinerRepositoryInterface $minerRepository,
        SummaryGetHandlerInterface $summaryGetHandler
    )
    {
        $this->minerRepository = $minerRepository;
        $this->authenticationAdapter = $authenticationAdapter;
        $this->summaryGetHandler = $summaryGetHandler;
    }

    /**
     * @param MinerSummaryGetCommandInterface $command
     * @return array
     * @throws MinerSummaryGetInvalidMinerException
     */
    public function execute(MinerSummaryGetCommandInterface $command): array
    {
        $user = $this->authenticationAdapter->execute();

        $miner = $this->minerRepository->getMinerByIdAndUser($command->getId(), $user);

        if (null === $miner) {
            throw new MinerSummaryGetInvalidMinerException('Invalid miner');
        }

        return $this->summaryGetHandler->handler($miner);
    }
}