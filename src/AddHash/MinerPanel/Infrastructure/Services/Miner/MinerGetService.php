<?php

namespace App\AddHash\MinerPanel\Infrastructure\Services\Miner;

use App\AddHash\MinerPanel\Domain\Miner\MinerRepositoryInterface;
use App\AddHash\MinerPanel\Domain\Miner\Command\MinerGetCommandInterface;
use App\AddHash\MinerPanel\Domain\Miner\Services\MinerGetServiceInterface;
use App\AddHash\MinerPanel\Infrastructure\Transformers\Miner\MinerTransform;
use App\AddHash\MinerPanel\Domain\Miner\Exceptions\MinerGetInvalidMinerException;
use App\AddHash\MinerPanel\Domain\Miner\MinerInfo\MinerInfoPoolGetHandlerInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerInfo\MinerInfoSummaryGetHandlerInterface;
use App\AddHash\MinerPanel\Domain\User\Services\UserAuthenticationGetServiceInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerCalcIncome\MinerCalcIncomeHandlerInterface;

final class MinerGetService implements MinerGetServiceInterface
{
    private $authenticationAdapter;

    private $minerRepository;

    private $summaryGetHandler;

    private $poolGetHandler;

    private $calcIncomeHandler;

    public function __construct(
        UserAuthenticationGetServiceInterface $authenticationAdapter,
        MinerRepositoryInterface $minerRepository,
        MinerInfoSummaryGetHandlerInterface $summaryGetHandler,
        MinerInfoPoolGetHandlerInterface $poolGetHandler,
        MinerCalcIncomeHandlerInterface $calcIncomeHandler
    )
    {
        $this->authenticationAdapter = $authenticationAdapter;
        $this->minerRepository = $minerRepository;
        $this->summaryGetHandler = $summaryGetHandler;
        $this->poolGetHandler = $poolGetHandler;
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

        $minerCredential = $miner->getCredential();

        $minerInfo = [
            'summary' => $this->summaryGetHandler->handler($minerCredential),
            'pools'   => $this->poolGetHandler->handler($minerCredential),
            'coins'   => $this->calcIncomeHandler->handler($miner),
        ];

        return (new MinerTransform())->transform($miner) + $minerInfo;
    }
}