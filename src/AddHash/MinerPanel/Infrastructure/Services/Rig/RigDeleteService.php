<?php

namespace App\AddHash\MinerPanel\Infrastructure\Services\Rig;

use App\AddHash\MinerPanel\Domain\Rig\RigRepositoryInterface;
use App\AddHash\MinerPanel\Domain\Rig\Command\RigDeleteCommandInterface;
use App\AddHash\MinerPanel\Domain\Rig\Services\RigDeleteServiceInterface;
use App\AddHash\MinerPanel\Domain\Rig\Exceptions\RigDeleteInvalidMinerException;
use App\AddHash\MinerPanel\Domain\User\Services\UserAuthenticationGetServiceInterface;

final class RigDeleteService implements RigDeleteServiceInterface
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
     * @param RigDeleteCommandInterface $command
     * @throws RigDeleteInvalidMinerException
     */
    public function execute(RigDeleteCommandInterface $command): void
    {
        $user = $this->authenticationAdapter->execute();

        $rig = $this->rigRepository->existRigByIdAndUser($command->getId(), $user);

        if (null === $rig) {
            throw new RigDeleteInvalidMinerException('Invalid rig');
        }

        $this->rigRepository->delete($rig);
    }
}