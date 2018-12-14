<?php

namespace App\AddHash\MinerPanel\Infrastructure\Services\Rig;

use App\AddHash\MinerPanel\Domain\Rig\RigRepositoryInterface;
use App\AddHash\MinerPanel\Domain\Rig\Command\RigGetCommandInterface;
use App\AddHash\MinerPanel\Domain\Rig\Services\RigGetServiceInterface;
use App\AddHash\MinerPanel\Infrastructure\Transformers\Rig\RigTransform;
use App\AddHash\MinerPanel\Domain\Rig\Exceptions\RigGetInvalidMinerException;
use App\AddHash\MinerPanel\Domain\User\Services\UserAuthenticationGetServiceInterface;

final class RigGetService implements RigGetServiceInterface
{
    private $authenticationAdapter;

    private $rigRepository;

    public function __construct(
        UserAuthenticationGetServiceInterface $authenticationAdapter,
        RigRepositoryInterface $rigRepository
    )
    {
        $this->authenticationAdapter = $authenticationAdapter;
        $this->rigRepository = $rigRepository;
    }

    /**
     * @param RigGetCommandInterface $command
     * @return array
     * @throws RigGetInvalidMinerException
     */
    public function execute(RigGetCommandInterface $command): array
    {
        $user = $this->authenticationAdapter->execute();

        $rig = $this->rigRepository->getRigByIdAndUser($command->getId(), $user);

        if (null === $rig) {
            throw new RigGetInvalidMinerException('Invalid rig');
        }

        return (new RigTransform())->transform($rig);
    }
}