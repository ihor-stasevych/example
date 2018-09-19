<?php

namespace App\AddHash\AdminPanel\Domain\User\Services\Miner\Pool\Strategy;

use App\AddHash\AdminPanel\Domain\Miners\MinerStock;
use App\AddHash\AdminPanel\Domain\User\Command\Miner\Pool\UserMinerControlPoolCommandInterface;

interface UserMinerControlPoolStrategyInterface
{
    public function canProcess(string $strategyAlias);

    public function process(MinerStock $minerStock, UserMinerControlPoolCommandInterface $command): array;
}