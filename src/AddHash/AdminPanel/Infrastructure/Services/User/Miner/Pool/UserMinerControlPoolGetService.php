<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\Miner\Pool;

use App\AddHash\AdminPanel\Domain\User\User;
use App\AddHash\AdminPanel\Domain\Miners\MinerStock;
use App\AddHash\AdminPanel\Domain\User\Order\UserOrderMiner;
use App\AddHash\AdminPanel\Infrastructure\Miners\Extender\MinerSocket;
use App\AddHash\AdminPanel\Infrastructure\Miners\Commands\MinerCommand;
use App\AddHash\AdminPanel\Infrastructure\Miners\Parsers\MinerSocketParser;
use App\AddHash\AdminPanel\Domain\User\Exceptions\MinerControlNoMainerException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\AddHash\AdminPanel\Domain\User\Command\Miner\Pool\UserMinerControlPoolGetCommandInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Miner\Pool\UserMinerControlPoolGetServiceInterface;

class UserMinerControlPoolGetService implements UserMinerControlPoolGetServiceInterface
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param UserMinerControlPoolGetCommandInterface $command
     * @return array
     * @throws MinerControlNoMainerException
     */
    public function execute(UserMinerControlPoolGetCommandInterface $command): array
	{
        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();

        if (!count($user->getOrderMiner())) {
            throw new MinerControlNoMainerException('No mainer');
        }

        $data = [];
        $id = $command->getId();

		/** @var UserOrderMiner $orderMiners */
		foreach ($user->getOrderMiner() as $orderMiners) {
			/** @var MinerStock $minerStock */
			foreach ($orderMiners->getMiners() as $minerStock) {
				if ($minerStock->getId() != $id) {
				    continue;
                }

                $command = new MinerCommand(
                    new MinerSocket($minerStock),
                    new MinerSocketParser()
                );

                $data = $command->getPools() + [
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