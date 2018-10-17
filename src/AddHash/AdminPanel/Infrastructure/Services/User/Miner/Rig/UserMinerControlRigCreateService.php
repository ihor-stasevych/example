<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\Miner\Rig;

use App\AddHash\AdminPanel\Domain\Miners\MinerStock;
use App\AddHash\AdminPanel\Domain\User\Services\UserGetAuthenticationServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Command\Miner\Rig\UserMinerControlRigCreateCommandInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Miner\Rig\UserMinerControlRigCreateServiceInterface;

class UserMinerControlRigCreateService implements UserMinerControlRigCreateServiceInterface
{
    private $authenticationService;

    public function __construct(UserGetAuthenticationServiceInterface $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    public function execute(UserMinerControlRigCreateCommandInterface $command): array
    {
        $user = $this->authenticationService->execute();

        foreach ($user->getOrderMiner() as $orderMiners) {
            /** @var MinerStock $minerStock */
            foreach ($orderMiners->getMiners() as $minerStock) {
                dd($minerStock);
            }
        }
    }
}