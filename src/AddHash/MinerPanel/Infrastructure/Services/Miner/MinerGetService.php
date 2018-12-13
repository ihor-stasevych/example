<?php

namespace App\AddHash\MinerPanel\Infrastructure\Services\Miner;

use App\AddHash\MinerPanel\Domain\Miner\MinerRepositoryInterface;
use App\AddHash\MinerPanel\Domain\Miner\Command\MinerGetCommandInterface;
use App\AddHash\MinerPanel\Domain\Miner\Services\MinerGetServiceInterface;
use App\AddHash\MinerPanel\Infrastructure\Transformers\Miner\MinerTransform;
use App\AddHash\MinerPanel\Domain\Miner\Exceptions\MinerGetInvalidMinerException;
use App\AddHash\MinerPanel\Domain\Miner\MinerInfo\MinerInfoPoolsGetHandlerInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerInfo\MinerInfoSummaryGetHandlerInterface;
use App\AddHash\MinerPanel\Domain\User\Services\UserAuthenticationGetServiceInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerCalcIncome\MinerCalcIncomeHandlerInterface;

final class MinerGetService implements MinerGetServiceInterface
{
    private $authenticationAdapter;

    private $minerRepository;

    private $summaryGetHandler;

    private $poolsGetHandler;

    private $calcIncomeHandler;

    public function __construct(
        UserAuthenticationGetServiceInterface $authenticationAdapter,
        MinerRepositoryInterface $minerRepository,
        MinerInfoSummaryGetHandlerInterface $summaryGetHandler,
        MinerInfoPoolsGetHandlerInterface $poolsGetHandler,
        MinerCalcIncomeHandlerInterface $calcIncomeHandler
    )
    {
        $this->authenticationAdapter = $authenticationAdapter;
        $this->minerRepository = $minerRepository;
        $this->summaryGetHandler = $summaryGetHandler;
        $this->poolsGetHandler = $poolsGetHandler;
        $this->calcIncomeHandler = $calcIncomeHandler;
    }

    /**
     * @param MinerGetCommandInterface $command
     * @return array
     * @throws MinerGetInvalidMinerException
     */
    public function execute(MinerGetCommandInterface $command): array
    {
        $user = $this->authenticationAdapter->execute();

        $miner = $this->minerRepository->getMinerByIdAndUser($command->getId(), $user);

        if (null === $miner) {
            throw new MinerGetInvalidMinerException('Invalid miner');
        }

        $summary = $this->summaryGetHandler->handler($miner);

        $pools = $this->poolsGetHandler->handler($miner);

        $coins['coins'] = $this->calcIncomeHandler->handler($miner);

        return (new MinerTransform())->transform($miner) + $summary + $pools + $coins;
    }
}