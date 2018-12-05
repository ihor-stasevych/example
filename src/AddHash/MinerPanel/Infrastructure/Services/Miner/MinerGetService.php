<?php

namespace App\AddHash\MinerPanel\Infrastructure\Services\Miner;

use App\AddHash\MinerPanel\Domain\Miner\Command\MinerGetCommandInterface;
use App\AddHash\MinerPanel\Domain\Miner\Services\MinerGetServiceInterface;
use App\AddHash\MinerPanel\Domain\Miner\Repository\MinerRepositoryInterface;
use App\AddHash\MinerPanel\Infrastructure\Transformers\Miner\MinerTransform;
use App\AddHash\MinerPanel\Domain\Miner\Exceptions\MinerGetInvalidMinerException;
use App\AddHash\MinerPanel\Domain\User\Services\UserAuthenticationGetServiceInterface;

final class MinerGetService implements MinerGetServiceInterface
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

        return (new MinerTransform())->transform($miner);
    }
}