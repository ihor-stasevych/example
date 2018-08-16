<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User;

use App\AddHash\AdminPanel\Domain\Miners\MinerStock;
use App\AddHash\AdminPanel\Domain\User\Order\UserOrderMiner;
use App\AddHash\AdminPanel\Domain\User\User;
use App\AddHash\AdminPanel\Domain\Miners\Miner;
use App\AddHash\AdminPanel\Infrastructure\Miners\Extender\MinerSocket;
use App\AddHash\AdminPanel\Infrastructure\Miners\Commands\MinerCommand;
use App\AddHash\AdminPanel\Infrastructure\Miners\Parsers\MinerSocketParser;
use App\AddHash\AdminPanel\Domain\User\Exceptions\MinerControlNoMainerException;
use App\AddHash\AdminPanel\Domain\User\Command\MinerControlPoolGetCommandInterface;
use App\AddHash\AdminPanel\Domain\User\Services\MinerControlGetPoolServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Exceptions\MinerControlNoMainerExistException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MinerControlGetPoolService implements MinerControlGetPoolServiceInterface
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param MinerControlPoolGetCommandInterface $command
     * @return array
     * @throws MinerControlNoMainerException
     * @throws MinerControlNoMainerExistException
     */
    public function execute(MinerControlPoolGetCommandInterface $command): array
	{
        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();

        if (!count($user->getOrderMiner())) {
            throw new MinerControlNoMainerException('No mainer');
        }

        $data = null;
        $id = $command->getId();

		/** @var UserOrderMiner $orderMiners */
		foreach ($user->getOrderMiner() as $orderMiners) {
			/** @var MinerStock $stock */
			foreach ($orderMiners->getMiners() as $stock) {
				if ($stock->getId() == $id) {
					$parser = new MinerSocketParser();
					$command = new MinerCommand(new MinerSocket($stock, $parser));

					$data = $command->getPools() + [
							'minerTitle' => $stock->infoMiner()->getTitle(),
							'minerId' => $stock->infoMiner()->getId(),
							'minerStockId' => $stock->getId(),
						];

					break;
				}
			}
		}

		/**
		 * if (null === $data) {
		throw new MinerControlNoMainerExistException('No mine exists');
		}
		 */


        return $data;
	}
}