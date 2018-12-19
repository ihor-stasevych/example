<?php

namespace App\AddHash\MinerPanel\Infrastructure\Services\Rig;

use App\AddHash\MinerPanel\Domain\Rig\Rig;
use App\AddHash\System\Response\ResponseListCollection;
use App\AddHash\MinerPanel\Domain\Rig\RigRepositoryInterface;
use App\AddHash\MinerPanel\Domain\Rig\Command\RigListCommandInterface;
use App\AddHash\MinerPanel\Domain\Rig\Services\RigListServiceInterface;
use App\AddHash\MinerPanel\Infrastructure\Transformers\Rig\RigTransform;
use App\AddHash\MinerPanel\Domain\User\Services\UserAuthenticationGetServiceInterface;

final class RigListService implements RigListServiceInterface
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

    public function execute(RigListCommandInterface $command): ResponseListCollection
    {
        $user = $this->authenticationAdapter->execute();

        $rigs = $this->rigRepository->getRigsByUser($user, $command->getPage());

        $data = [];

        if ($rigs->count() > 0) {
            $transform = new RigTransform();

            /** @var Rig $rig */
            foreach ($rigs as $rig) {
                $data[] = $transform->transform($rig);
            }
        }

        return new ResponseListCollection(
            $data,
            $rigs->getNbResults(),
            $rigs->getNbPages(),
            $rigs->getCurrentPage(),
            $rigs->getMaxPerPage()
        );
    }
}