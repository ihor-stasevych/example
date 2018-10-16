<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\Miner\Pool\Strategy;

use App\AddHash\AdminPanel\Domain\Miners\MinerStock;
use App\AddHash\AdminPanel\Infrastructure\Miners\Extender\MinerSocket;
use App\AddHash\AdminPanel\Infrastructure\Miners\Commands\MinerCommand;
use App\AddHash\AdminPanel\Infrastructure\Miners\Parsers\MinerSocketParser;
use App\AddHash\AdminPanel\Domain\User\Services\Miner\Pool\Strategy\UserMinerControlPoolStrategyInterface;
use App\AddHash\AdminPanel\Domain\User\Command\Miner\Pool\UserMinerControlPoolCommandInterface;


class UserMinerControlPoolGetStrategy implements UserMinerControlPoolStrategyInterface
{
    const STRATEGY_ALIAS = 'pool_get';

    public function canProcess(string $strategyAlias)
    {
        return static::STRATEGY_ALIAS == $strategyAlias;
    }

    public function process(MinerStock $minerStock, UserMinerControlPoolCommandInterface $command): array
    {
        $minerCommand = new MinerCommand(
            new MinerSocket($minerStock),
            new MinerSocketParser()
        );
		$period = $minerStock;
        return $minerCommand->getPools() + [
            'minerTitle'   => $minerStock->infoMiner()->getTitle(),
            'minerId'      => $minerStock->infoMiner()->getId(),
            'minerStockId' => $minerStock->getId(),
        ];
    }
}