<?php

namespace App\AddHash\AdminPanel\Domain\User\Services\Miner\Pool;

use App\AddHash\AdminPanel\Domain\User\Command\Miner\Pool\UserMinerControlPoolCommandInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Miner\Pool\Strategy\UserMinerControlPoolStrategyInterface;

interface UserMinerControlPoolContextInterface
{
    public function addStrategy(UserMinerControlPoolStrategyInterface $strategy);

    public function handle(string $strategyAlias, UserMinerControlPoolCommandInterface $command): array;
}