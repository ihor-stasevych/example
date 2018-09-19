<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\Miner\Pool;

use App\AddHash\AdminPanel\Domain\User\User;
use App\AddHash\AdminPanel\Domain\Miners\MinerStock;
use App\AddHash\AdminPanel\Domain\User\Exceptions\MinerControlNoMainerException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\AddHash\AdminPanel\Domain\User\Command\Miner\Pool\UserMinerControlPoolCommandInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Miner\Pool\UserMinerControlPoolContextInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Miner\Pool\Strategy\UserMinerControlPoolStrategyInterface;

class UserMinerControlPoolContext implements UserMinerControlPoolContextInterface
{
    private $strategies = [];

    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function addStrategy(UserMinerControlPoolStrategyInterface $strategy)
    {
        $this->strategies[] = $strategy;
    }

    /**
     * @param string $strategyAlias
     * @param UserMinerControlPoolCommandInterface $command
     * @return array
     * @throws MinerControlNoMainerException
     */
    public function handle(string $strategyAlias, UserMinerControlPoolCommandInterface $command): array
    {
        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();
        $data = [];

        if (!count($user->getOrderMiner())) {
            throw new MinerControlNoMainerException('No mainer');
        }

        /** @var UserMinerControlPoolStrategyInterface $strategy */
        foreach ($this->strategies as $strategy) {
            if (false === $strategy->canProcess($strategyAlias)) {
                continue;
            }

            foreach ($user->getOrderMiner() as $orderMiners) {
                /** @var MinerStock $minerStock */
                foreach ($orderMiners->getMiners() as $minerStock) {
                    if ($minerStock->getId() == $command->getMinerId()) {
                        return $strategy->process($minerStock, $command);
                    }
                }
            }

        }

        return $data;
    }
}