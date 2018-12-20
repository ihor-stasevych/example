<?php

namespace App\AddHash\MinerPanel\Infrastructure\Services\Rig;

use App\AddHash\MinerPanel\Domain\Rig\Rig;
use App\AddHash\MinerPanel\Domain\Rig\RigRepositoryInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerRepositoryInterface;
use App\AddHash\MinerPanel\Infrastructure\Transformers\Rig\RigTransform;
use App\AddHash\MinerPanel\Domain\Rig\Command\RigCreateCommandInterface;
use App\AddHash\MinerPanel\Domain\Rig\Services\RigCreateServiceInterface;
use App\AddHash\MinerPanel\Domain\Rig\Exceptions\RigCreateTitleExistsException;
use App\AddHash\MinerPanel\Domain\Rig\Exceptions\RigCreateInvalidMinersIdException;
use App\AddHash\MinerPanel\Domain\User\Services\UserAuthenticationGetServiceInterface;

final class RigCreateService implements RigCreateServiceInterface
{
    private $authenticationAdapter;

    private $rigRepository;

    private $minerRepository;

    public function __construct(
        UserAuthenticationGetServiceInterface $authenticationAdapter,
        RigRepositoryInterface $rigRepository,
        MinerRepositoryInterface $minerRepository
    )
    {
        $this->authenticationAdapter = $authenticationAdapter;
        $this->rigRepository = $rigRepository;
        $this->minerRepository = $minerRepository;
    }

    /**
     * @param RigCreateCommandInterface $command
     * @return array
     * @throws RigCreateInvalidMinersIdException
     * @throws RigCreateTitleExistsException
     */
    public function execute(RigCreateCommandInterface $command): array
    {
        $user = $this->authenticationAdapter->execute();

        $title = $command->getTitle();
        $rig = $this->rigRepository->getRigByTitleAndUser($title, $user);

        if (null !== $rig) {
            throw new RigCreateTitleExistsException(['title' => ['Title already exists']]);
        }

        $minersId = $command->getMinersId();

        $miners = [];

        if (null !== $minersId) {
            $miners = $this->minerRepository->getMinersByIdsAndUserAndNoRig($minersId, $user);

            if (count($minersId) != count($miners)) {
                throw new RigCreateInvalidMinersIdException('Invalid miners id');
            }
        }

        $rig = new Rig(
            $title,
            $command->getWorker(),
            $command->getUrl(),
            $command->getPassword(),
            $user
        );

        foreach ($miners as $miner) {
            $rig->setMiner($miner);
        }

        $this->rigRepository->save($rig);

        $rig = $this->rigRepository->getRigById($rig->getId());

        #ToDo change pools miners

        return (new RigTransform())->transform($rig);
    }
}