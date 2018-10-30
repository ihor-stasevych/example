<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\Miner\Pool;

use App\AddHash\AdminPanel\Domain\Miners\MinerStock;
use App\AddHash\AdminPanel\Infrastructure\Miners\Extender\MinerSocket;
use App\AddHash\AdminPanel\Infrastructure\Miners\Commands\MinerCommand;
use App\AddHash\AdminPanel\Infrastructure\Miners\Parsers\MinerSocketParser;
use App\AddHash\AdminPanel\Domain\User\Order\UserOrderMinerRepositoryInterface;
use App\AddHash\AdminPanel\Domain\User\Exceptions\MinerControlNoMainerException;
use App\AddHash\AdminPanel\Domain\User\Services\UserGetAuthenticationServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Miner\Pool\UserMinerPoolGetServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Command\Miner\Pool\UserMinerControlPoolCommandInterface;

final class UserMinerPoolGetService implements UserMinerPoolGetServiceInterface
{
    private $authenticationService;

    private $userOrderMinerRepository;

    public function __construct(
        UserGetAuthenticationServiceInterface $authenticationService,
        UserOrderMinerRepositoryInterface $userOrderMinerRepository
    )
    {
        $this->authenticationService = $authenticationService;
        $this->userOrderMinerRepository = $userOrderMinerRepository;
    }

    /**
     * @param UserMinerControlPoolCommandInterface $command
     * @return array
     * @throws MinerControlNoMainerException
     */
    public function execute(UserMinerControlPoolCommandInterface $command): array
    {
        $user = $this->authenticationService->execute();

        $userOrderMiner = $this->userOrderMinerRepository->getByUserAndMinerStockId($user, $command->getMinerId());

        if (null === $userOrderMiner) {
            throw new MinerControlNoMainerException('No mainer');
        }

        /** @var MinerStock $minerStock */
        $minerStock = $userOrderMiner->getMiners()->first();

        $minerCommand = new MinerCommand(
            new MinerSocket($minerStock),
            new MinerSocketParser()
        );

        return $minerCommand->getPools() + [
            'minerTitle'   => $minerStock->infoMiner()->getTitle(),
            'rentPeriod'   => $userOrderMiner->getRentPeriod(),
            'minerId'      => $minerStock->infoMiner()->getId(),
            'minerStockId' => $minerStock->getId(),
        ];
    }
}