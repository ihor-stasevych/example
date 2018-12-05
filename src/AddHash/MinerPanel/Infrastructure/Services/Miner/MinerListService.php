<?php

namespace App\AddHash\MinerPanel\Infrastructure\Services\Miner;

use App\AddHash\MinerPanel\Domain\Miner\Model\Miner;
use App\AddHash\MinerPanel\Domain\Miner\Services\MinerListServiceInterface;
use App\AddHash\MinerPanel\Domain\Miner\Repository\MinerRepositoryInterface;
use App\AddHash\MinerPanel\Infrastructure\Transformers\Miner\MinerTransform;
use App\AddHash\MinerPanel\Domain\User\Services\UserAuthenticationGetServiceInterface;

final class MinerListService implements MinerListServiceInterface
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

        if (count($miners) > 0) {
            $transform = new MinerTransform();

            /** @var Miner $miner */
            foreach ($miners as $miner) {
                $data[] = $transform->transform($miner);
            }
        }

        return $data;
    }
}