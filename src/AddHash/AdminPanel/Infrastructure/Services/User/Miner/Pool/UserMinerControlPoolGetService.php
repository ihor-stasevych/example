<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\Miner\Pool;

use App\AddHash\AdminPanel\Domain\Miners\MinerStock;
use App\AddHash\AdminPanel\Infrastructure\Miners\Extender\MinerSocket;
use App\AddHash\AdminPanel\Infrastructure\Miners\Commands\MinerCommand;
use App\AddHash\AdminPanel\Infrastructure\Miners\Parsers\MinerSocketParser;
use App\AddHash\AdminPanel\Domain\User\Command\Miner\UserMinerControlCommandInterface;
use App\AddHash\AdminPanel\Domain\User\Command\Miner\Pool\UserMinerControlPoolGetCommandInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Miner\Pool\UserMinerControlPoolGetServiceInterface;

class UserMinerControlPoolGetService implements UserMinerControlPoolGetServiceInterface
{
    /**
     * @param UserMinerControlPoolGetCommandInterface $command
     * @param MinerStock $minerStock
     * @return array
     */
    public function execute(UserMinerControlCommandInterface $command, MinerStock $minerStock): array
	{
	    $data = [];

        if ($minerStock->getId() != $command->getMinerId()) {
            return $data;
        }

        $command = new MinerCommand(
            new MinerSocket($minerStock),
            new MinerSocketParser()
        );

        return $command->getPools() + [
            'minerTitle'   => $minerStock->infoMiner()->getTitle(),
            'minerId'      => $minerStock->infoMiner()->getId(),
            'minerStockId' => $minerStock->getId(),
        ];
	}
}