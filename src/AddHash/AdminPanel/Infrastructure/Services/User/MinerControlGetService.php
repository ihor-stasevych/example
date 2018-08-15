<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User;

use App\AddHash\AdminPanel\Domain\Miners\MinerStock;
use App\AddHash\AdminPanel\Domain\User\Order\UserOrderMiner;
use App\AddHash\AdminPanel\Domain\User\User;
use App\AddHash\AdminPanel\Domain\Miners\Miner;
use App\AddHash\AdminPanel\Infrastructure\Miners\Extender\MinerSocket;
use App\AddHash\AdminPanel\Infrastructure\Miners\Commands\MinerCommand;
use App\AddHash\AdminPanel\Infrastructure\Miners\Parsers\MinerSocketParser;
use App\AddHash\AdminPanel\Domain\User\Services\MinerControlGetServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Exceptions\MinerControlNoMainerException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MinerControlGetService implements MinerControlGetServiceInterface
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return array
     * @throws MinerControlNoMainerException
     */
    public function execute(): array
	{
	    /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();

        if (!count($user->getOrderMiner())) {
            throw new MinerControlNoMainerException('No mainer');
        }

        $parser = new MinerSocketParser();
        $data = [];

        /** @var UserOrderMiner $orderMiners */
		foreach ($user->getOrderMiner() as $orderMiners) {
			/** @var MinerStock $stock */
			foreach ($orderMiners->getMiners() as $stock) {
                    $command = new MinerCommand(new MinerSocket($stock, $parser));

                    $data[] = $command->getSummary() + [
                        'minerTitle'   => $stock->getMiner()->getTitle(),
                        'minerId'      => $stock->getMiner()->getId(),
                        'minerStockId' => $stock->getId(),
                    ];

            }
        }

        return $data;
	}
}