<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\Miner\Strategy;

use App\AddHash\AdminPanel\Domain\User\User;
use App\AddHash\AdminPanel\Domain\Miners\MinerStock;
use App\AddHash\AdminPanel\Domain\User\Exceptions\MinerControlNoMainerException;
use App\AddHash\AdminPanel\Domain\User\Command\Miner\UserMinerControlCommandInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Miner\UserMinerControlServiceInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Miner\Strategy\UserMinerControlStrategyInterface;

class UserMinerControlStrategy implements UserMinerControlStrategyInterface
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param UserMinerControlCommandInterface $command
     * @param UserMinerControlServiceInterface $service
     * @return array
     * @throws MinerControlNoMainerException
     */
    public function execute(UserMinerControlCommandInterface $command, UserMinerControlServiceInterface $service)
    {
        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();
        $data = [];

        if (!count($user->getOrderMiner())) {
            throw new MinerControlNoMainerException('No mainer');
        }

        foreach ($user->getOrderMiner() as $orderMiners) {
            /** @var MinerStock $minerStock */
            foreach ($orderMiners->getMiners() as $minerStock) {
                $data += $service->execute($command, $minerStock);

                if ($data) {
                    break;
                }
            }
        }

        return $data;
    }
}