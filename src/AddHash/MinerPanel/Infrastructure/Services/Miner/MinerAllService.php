<?php

namespace App\AddHash\MinerPanel\Infrastructure\Services\Miner;

use App\AddHash\MinerPanel\Domain\Miner\MinerRepositoryInterface;
use App\AddHash\MinerPanel\Domain\Miner\Services\MinerAllServiceInterface;
use App\AddHash\MinerPanel\Infrastructure\Transformers\Miner\MinerAllTransform;
use App\AddHash\MinerPanel\Domain\User\Services\UserAuthenticationGetServiceInterface;

final class MinerAllService implements MinerAllServiceInterface
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

    public function execute(): array
    {
        $user = $this->authenticationAdapter->execute();

        $miners = $this->minerRepository->getMinersByUser($user);

        $data = [];
        $minerAllTransform = new MinerAllTransform();

        foreach ($miners as $miner) {
            $data[] = $minerAllTransform->transform($miner);
        }

        return $data;
    }
}