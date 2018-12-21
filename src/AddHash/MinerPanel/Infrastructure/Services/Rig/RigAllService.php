<?php

namespace App\AddHash\MinerPanel\Infrastructure\Services\Rig;

use App\AddHash\MinerPanel\Domain\Rig\RigRepositoryInterface;
use App\AddHash\MinerPanel\Domain\Rig\Services\RigAllServiceInterface;
use App\AddHash\MinerPanel\Infrastructure\Transformers\Rig\RigAllTransform;
use App\AddHash\MinerPanel\Domain\User\Services\UserAuthenticationGetServiceInterface;

final class RigAllService implements RigAllServiceInterface
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

    public function execute(): array
    {
        $user = $this->authenticationAdapter->execute();

        $rigs = $this->rigRepository->getRigsByUser($user);

        $data = [];
        $rigAllTransform = new RigAllTransform();

        foreach ($rigs as $rig) {
            $data[] = $rigAllTransform->transform($rig);
        }

        return $data;
    }
}