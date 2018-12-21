<?php

namespace App\AddHash\MinerPanel\Infrastructure\Services\Miner\MinerPool;

use App\AddHash\MinerPanel\Domain\Miner\MinerRepositoryInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerInfo\MinerInfoPoolGetHandlerInterface;
use App\AddHash\MinerPanel\Domain\User\Services\UserAuthenticationGetServiceInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerPool\Command\MinerPoolGetCommandInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerPool\Services\MinerPoolGetServiceInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerPool\Exceptions\MinerPoolGetInvalidMinerException;

final class MinerPoolGetService implements MinerPoolGetServiceInterface
{
    private $authenticationAdapter;

    private $minerRepository;

    private $poolGetHandler;

    public function __construct(
        UserAuthenticationGetServiceInterface $authenticationAdapter,
        MinerRepositoryInterface $minerRepository,
        MinerInfoPoolGetHandlerInterface $poolGetHandler
    )
    {
        $this->minerRepository = $minerRepository;
        $this->authenticationAdapter = $authenticationAdapter;
        $this->poolGetHandler = $poolGetHandler;
    }

    /**
     * @param MinerPoolGetCommandInterface $command
     * @return array
     * @throws MinerPoolGetInvalidMinerException
     */
    public function execute(MinerPoolGetCommandInterface $command): array
    {
        $user = $this->authenticationAdapter->execute();

        $miner = $this->minerRepository->existMinerByIdAndUser($command->getId(), $user);

        if (null === $miner) {
            throw new MinerPoolGetInvalidMinerException('Invalid miner');
        }

        return $this->poolGetHandler->handler($miner->getCredential());
    }
}