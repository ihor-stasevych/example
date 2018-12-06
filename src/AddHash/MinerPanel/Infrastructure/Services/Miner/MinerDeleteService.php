<?php

namespace App\AddHash\MinerPanel\Infrastructure\Services\Miner;

use App\AddHash\MinerPanel\Domain\Miner\Command\MinerDeleteCommandInterface;
use App\AddHash\MinerPanel\Domain\Miner\Repository\MinerRepositoryInterface;
use App\AddHash\MinerPanel\Domain\Miner\Services\MinerDeleteServiceInterface;
use App\AddHash\MinerPanel\Domain\Miner\Exceptions\MinerDeleteInvalidMinerException;
use App\AddHash\MinerPanel\Domain\User\Services\UserAuthenticationGetServiceInterface;

final class MinerDeleteService implements MinerDeleteServiceInterface
{
    private $authenticationAdapter;

    private $minerRepository;

    public function __construct(
        UserAuthenticationGetServiceInterface $authenticationAdapter,
        MinerRepositoryInterface $minerRepository
    )
    {
        $this->authenticationAdapter = $authenticationAdapter;
        $this->minerRepository = $minerRepository;
    }

    /**
     * @param MinerDeleteCommandInterface $command
     * @throws MinerDeleteInvalidMinerException
     */
    public function execute(MinerDeleteCommandInterface $command): void
    {
        $user = $this->authenticationAdapter->execute();

        $miner = $this->minerRepository->getMinerByIdAndUser($command->getId(), $user);

        if (null === $miner) {
            throw new MinerDeleteInvalidMinerException('Invalid miner');
        }

        $this->minerRepository->delete($miner);
    }
}