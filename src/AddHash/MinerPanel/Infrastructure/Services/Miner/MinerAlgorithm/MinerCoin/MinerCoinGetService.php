<?php

namespace App\AddHash\MinerPanel\Infrastructure\Services\Miner\MinerAlgorithm\MinerCoin;

use App\AddHash\MinerPanel\Domain\Miner\MinerRepositoryInterface;
use App\AddHash\MinerPanel\Domain\User\Services\UserAuthenticationGetServiceInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerCalcIncome\MinerCalcIncomeHandlerInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin\Command\MinerCoinGetCommandInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin\Services\MinerCoinGetServiceInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin\Exceptions\MinerCoinInvalidMinerException;

final class MinerCoinGetService implements MinerCoinGetServiceInterface
{
    private $authenticationAdapter;

    private $minerRepository;

    private $calcIncomeHandler;

    public function __construct(
        UserAuthenticationGetServiceInterface $authenticationAdapter,
        MinerRepositoryInterface $minerRepository,
        MinerCalcIncomeHandlerInterface $calcIncomeHandler
    )
    {
        $this->minerRepository = $minerRepository;
        $this->authenticationAdapter = $authenticationAdapter;
        $this->calcIncomeHandler = $calcIncomeHandler;
    }

    /**
     * @param MinerCoinGetCommandInterface $command
     * @return array
     * @throws MinerCoinInvalidMinerException
     */
    public function execute(MinerCoinGetCommandInterface $command): array
    {
        $user = $this->authenticationAdapter->execute();

        $miner = $this->minerRepository->existMinerByIdAndUser($command->getId(), $user);

        if (null === $miner) {
            throw new MinerCoinInvalidMinerException('Invalid miner');
        }

        return $this->calcIncomeHandler->handler($miner);
    }
}