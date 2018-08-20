<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\Miner\Pool;

use App\AddHash\AdminPanel\Domain\User\User;
use App\AddHash\AdminPanel\Domain\Miners\MinerStock;
use App\AddHash\AdminPanel\Domain\User\Order\UserOrderMiner;
use App\AddHash\AdminPanel\Infrastructure\Miners\Extender\MinerSocket;
use App\AddHash\AdminPanel\Infrastructure\Miners\Commands\MinerCommand;
use App\AddHash\AdminPanel\Infrastructure\Miners\Parsers\MinerSocketParser;
use App\AddHash\AdminPanel\Infrastructure\Miners\Parsers\MinerSocketStatusParser;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\AddHash\AdminPanel\Domain\User\Command\Miner\Pool\UserMinerControlPoolUpdateCommandInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Miner\Pool\UserMinerControlPoolUpdateServiceInterface;

class UserMinerControlPoolUpdateService implements UserMinerControlPoolUpdateServiceInterface
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function execute(UserMinerControlPoolUpdateCommandInterface $command)
    {
        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();

        $minerId = $command->getMinerId();
        $data = [];

        /** @var UserOrderMiner $orderMiners */
        foreach ($user->getOrderMiner() as $orderMiners) {
            /** @var MinerStock $minerStock */
            foreach ($orderMiners->getMiners() as $minerStock) {
                if ($minerStock->getId() != $minerId) {
                    continue;
                }

                /**
                 * Update
                 */

                $minerCommand = new MinerCommand(
                    new MinerSocket($minerStock),
                    new MinerSocketParser()
                );

                $data = $minerCommand->getPools() + [
                    'minerTitle'   => $minerStock->infoMiner()->getTitle(),
                    'minerId'      => $minerStock->infoMiner()->getId(),
                    'minerStockId' => $minerStock->getId(),
                ];

                break;
            }
        }

        return $data;
    }
}