<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\Miner\Rig;

use App\AddHash\Authentication\Domain\Model\User;
use App\AddHash\AdminPanel\Domain\Miners\MinerStock;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\AddHash\AdminPanel\Domain\User\Command\Miner\Rig\UserMinerControlRigCreateCommandInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Miner\Rig\UserMinerControlRigCreateServiceInterface;

class UserMinerControlRigCreateService implements UserMinerControlRigCreateServiceInterface
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function execute(UserMinerControlRigCreateCommandInterface $command): array
    {
        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();

        foreach ($user->getOrderMiner() as $orderMiners) {
            /** @var MinerStock $minerStock */
            foreach ($orderMiners->getMiners() as $minerStock) {
                dd($minerStock);
            }
        }
    }
}