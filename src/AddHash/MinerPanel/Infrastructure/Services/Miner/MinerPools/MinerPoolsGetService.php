<?php

namespace App\AddHash\MinerPanel\Infrastructure\Services\Miner\MinerPools;

use App\AddHash\MinerPanel\Domain\Miner\Repository\MinerRepositoryInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerInfo\MinerInfoPoolsGetHandlerInterface;
use App\AddHash\MinerPanel\Domain\User\Services\UserAuthenticationGetServiceInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerPools\Command\MinerPoolsGetCommandInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerPools\Services\MinerPoolsGetServiceInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerPools\Exceptions\MinerPoolsGetInvalidMinerException;

final class MinerPoolsGetService implements MinerPoolsGetServiceInterface
{
    private $authenticationAdapter;

    private $minerRepository;

    private $poolsGetHandler;

    public function __construct(
        UserAuthenticationGetServiceInterface $authenticationAdapter,
        MinerRepositoryInterface $minerRepository,
        MinerInfoPoolsGetHandlerInterface $poolsGetHandler
    )
    {
        $this->minerRepository = $minerRepository;
        $this->authenticationAdapter = $authenticationAdapter;
        $this->poolsGetHandler = $poolsGetHandler;
    }

    /**
     * @param MinerPoolsGetCommandInterface $command
     * @return array
     * @throws MinerPoolsGetInvalidMinerException
     */
    public function execute(MinerPoolsGetCommandInterface $command): array
    {
        $user = $this->authenticationAdapter->execute();

        $miner = $this->minerRepository->getMinerByIdAndUser($command->getId(), $user);

        if (null === $miner) {
            throw new MinerPoolsGetInvalidMinerException('Invalid miner');
        }

        return $this->poolsGetHandler->handler($miner);
    }
}