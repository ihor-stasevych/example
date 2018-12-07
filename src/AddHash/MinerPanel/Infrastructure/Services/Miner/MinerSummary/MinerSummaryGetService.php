<?php

namespace App\AddHash\MinerPanel\Infrastructure\Services\Miner\MinerSummary;

use App\AddHash\MinerPanel\Domain\Miner\MinerRepositoryInterface;
use App\AddHash\MinerPanel\Domain\User\Services\UserAuthenticationGetServiceInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerInfo\MinerInfoSummaryGetHandlerInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerSummary\Command\MinerSummaryGetCommandInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerSummary\Services\MinerSummaryGetServiceInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerSummary\Exceptions\MinerSummaryGetInvalidMinerException;

final class MinerSummaryGetService implements MinerSummaryGetServiceInterface
{
    private $authenticationAdapter;

    private $minerRepository;

    private $summaryGetHandler;

    public function __construct(
        UserAuthenticationGetServiceInterface $authenticationAdapter,
        MinerRepositoryInterface $minerRepository,
        MinerInfoSummaryGetHandlerInterface $summaryGetHandler
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